<?php
/**
 * ============================================
 * KATTA AI CHAT - Hovedside
 * ============================================
 * 
 * Dette er hovedsiden for chat-applikasjonen.
 * Her kan elevene se og chatte med AI-agenten.
 */

require_once 'config.php';
require_once 'auth.php';

// Krev innlogging for å se denne siden
requireLogin();
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(AGENT_NAME) ?> - AI Chat</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="app">
        <!-- Header -->
        <header class="header">
            <div class="header__icon">
                <i class="fas fa-robot"></i>
            </div>
            <div class="header__info">
                <h1 class="header__title"><?= htmlspecialchars(AGENT_NAME) ?></h1>
                <p class="header__subtitle"><?= htmlspecialchars(AGENT_DESCRIPTION) ?></p>
            </div>
            <div class="header__status">
                <span class="header__status-dot"></span>
                Online
            </div>
            <a href="logout.php" class="header__logout" title="Logg ut">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </header>
        
        <!-- Chat Container -->
        <div class="chat" id="chat">
            <!-- Welcome Message -->
            <div class="welcome" id="welcome">
                <div class="welcome__icon">
                    <i class="fas fa-robot"></i>
                </div>
                <h2 class="welcome__title">Hei! Jeg er <?= htmlspecialchars(AGENT_NAME) ?> 👋</h2>
                <p class="welcome__text">
                    Jeg er her for å hjelpe deg ved å forklare om noe du har gjort eller noe du har tenkt på er lovlig eller ikke. 
                    Still meg et spørsmål, så skal jeg gjøre mitt beste for å sikre deg ett troverdig svar!
                </p>
                <div class="welcome__suggestions">
                    <button class="welcome__suggestion" data-message="trenger jeg ID for å kjøpe energidrikk i Norge?">
                        🤪 Energidrikk
                    </button>
                    <button class="welcome__suggestion" data-message="Hva er straffen på mord?">
                        💀 Mord
                    </button>
                    <button class="welcome__suggestion" data-message="Hvor mange prikker på lappen kan man få for feil bruk av lys?">
                        🚗 Førekort
                    </button>
                    <button class="welcome__suggestion" data-message="Kan jeg bli arrestert for vape?">
                        🚬 Vape?
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Input Area -->
        <div class="input-area">
            <form class="input-area__form" id="chatForm">
                <div class="input-area__input-wrapper">
                    <textarea 
                        class="input-area__input" 
                        id="messageInput" 
                        placeholder="Skriv din melding her..."
                        rows="1"
                        autofocus
                    ></textarea>
                </div>
                <button type="submit" class="input-area__button" id="sendButton">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
            <p class="input-area__hint">
                Trykk Enter for å sende • Shift+Enter for ny linje
            </p>
        </div>
    </div>

    <script>
        // ============================================
        // CHAT JAVASCRIPT
        // ============================================
        
        const chat = document.getElementById('chat');
        const chatForm = document.getElementById('chatForm');
        const messageInput = document.getElementById('messageInput');
        const sendButton = document.getElementById('sendButton');
        const welcome = document.getElementById('welcome');
        
        // Samtalehistorikk for kontekst
        let conversationHistory = [];
        
        // Auto-resize textarea
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 150) + 'px';
        });
        
        // Håndter Enter-tast
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                chatForm.dispatchEvent(new Event('submit'));
            }
        });
        
        // Håndter forslags-knapper
        document.querySelectorAll('.welcome__suggestion').forEach(btn => {
            btn.addEventListener('click', function() {
                const message = this.dataset.message;
                messageInput.value = message;
                chatForm.dispatchEvent(new Event('submit'));
            });
        });
        
        // Håndter skjema-innsending
        chatForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const message = messageInput.value.trim();
            if (!message) return;
            
            // Skjul velkomstmelding
            if (welcome) {
                welcome.style.display = 'none';
            }
            
            // Legg til brukermelding
            addMessage(message, 'user');
            conversationHistory.push({ role: 'user', content: message });
            
            // Tøm input
            messageInput.value = '';
            messageInput.style.height = 'auto';
            
            // Vis typing-indikator
            const typingIndicator = showTypingIndicator();
            
            // Deaktiver input
            setInputState(false);
            
            try {
                // Send til API
                const response = await fetch('api/chat.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        message: message,
                        history: conversationHistory.slice(-10) // Send siste 10 meldinger for kontekst
                    })
                });
                
                const data = await response.json();
                
                // Fjern typing-indikator
                typingIndicator.remove();
                
                if (data.error) {
                    showError(data.error);
                } else {
                    addMessage(data.response, 'ai');
                    conversationHistory.push({ role: 'assistant', content: data.response });
                }
            } catch (error) {
                typingIndicator.remove();
                showError('Kunne ikke koble til serveren. Prøv igjen senere.');
            }
            
            // Reaktiver input
            setInputState(true);
            messageInput.focus();
        });
        
        // Legg til melding i chat
        function addMessage(content, type) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message message--${type}`;
            
            const avatar = document.createElement('div');
            avatar.className = 'message__avatar';
            avatar.innerHTML = type === 'user' 
                ? '<i class="fas fa-user"></i>' 
                : '<i class="fas fa-robot"></i>';
            
            const contentDiv = document.createElement('div');
            contentDiv.className = 'message__content';
            contentDiv.innerHTML = formatMessage(content);
            
            messageDiv.appendChild(avatar);
            messageDiv.appendChild(contentDiv);
            
            chat.appendChild(messageDiv);
            scrollToBottom();
        }
        
        // Formater melding (enkel markdown-støtte)
        function formatMessage(text) {
            // Escape HTML
            text = text.replace(/&/g, '&amp;')
                       .replace(/</g, '&lt;')
                       .replace(/>/g, '&gt;');
            
            // Kodeblokker
            text = text.replace(/```(\w*)\n?([\s\S]*?)```/g, '<pre><code>$2</code></pre>');
            
            // Inline kode
            text = text.replace(/`([^`]+)`/g, '<code>$1</code>');
            
            // Fet tekst
            text = text.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');
            
            // Kursiv
            text = text.replace(/\*([^*]+)\*/g, '<em>$1</em>');
            
            // Linjeskift til <br> og avsnitt
            text = text.split('\n\n').map(p => `<p>${p.replace(/\n/g, '<br>')}</p>`).join('');
            
            return text;
        }
        
        // Vis typing-indikator
        function showTypingIndicator() {
            const typing = document.createElement('div');
            typing.className = 'typing';
            typing.innerHTML = `
                <div class="typing__avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="typing__dots">
                    <span class="typing__dot"></span>
                    <span class="typing__dot"></span>
                    <span class="typing__dot"></span>
                </div>
            `;
            chat.appendChild(typing);
            scrollToBottom();
            return typing;
        }
        
        // Vis feilmelding
        function showError(message) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error';
            errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
            chat.appendChild(errorDiv);
            scrollToBottom();
        }
        
        // Aktiver/deaktiver input
        function setInputState(enabled) {
            messageInput.disabled = !enabled;
            sendButton.disabled = !enabled;
        }
        
        // Scroll til bunnen av chat
        function scrollToBottom() {
            chat.scrollTop = chat.scrollHeight;
        }
    </script>
</body>
</html>