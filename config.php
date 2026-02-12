<?php
/**
 * ============================================
 * KONFIGURASJON FOR KATTA AI CHAT
 * ============================================
 * 
 * VIKTIG FOR ELEVER:
 * Her kan du endre innstillingene for din AI-agent!
 * IKKE LAST OPP TIL GITHUB eller del etter at du har lagt inn API-nøkkelen i denne koden!
 * 
 * SYSTEM_PROMPT bestemmer hvordan AI-en skal oppføre seg.
 * Prøv å endre denne for å lage din egen spesialiserte agent!
 */

// OpenAI API-nøkkel (IKKE del denne med andre!)
define('OPENAI_API_KEY', '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'); // Husk apostrof før og etter nøkkelen

// Modell - gpt-4o-mini er rask og rimelig
define('OPENAI_MODEL', 'gpt-4o-mini');

// Maks tokens i svaret (jo høyere, jo lengre svar kan AI-en gi)
define('OPENAI_MAX_TOKENS', 1067);

// Temperatur - 0 = mer presis, 1 = mer kreativ
define('OPENAI_TEMPERATURE', 0.267);

/**
 * SYSTEM PROMPT - Dette er "personligheten" til AI-en din!
 * 
 * EKSEMPLER PÅ HVA DU KAN GJØRE:
 * 
 * 1. Skolehjelper:
 *    "Du er en hjelpsom lærerassistent på Hamar Katedralskole. 
 *     Du hjelper kun med skolerelaterte spørsmål."
 * 
 * 2. Mattetutor:
 *    "Du er en matematikklærer som forklarer konsepter steg for steg.
 *     Bruk alltid eksempler og forklar på en enkel måte."
 * 
 * 3. Historieekspert:
 *    "Du er en historiker som elsker å fortelle spennende historier
 *     om historiske hendelser. Svar alltid med fascinerende fakta."
 * 
 * 4. Programmerings-mentor:
 *    "Du er en programmerings-mentor som hjelper nybegynnere.
 *     Forklar kode på en enkel måte med gode eksempler."
 */
define('SYSTEM_PROMPT', 'Du er en hjelpsom AI-assistent for elever ved Hamar Katedralskole. 

Du skal:
- Svare på Engelsk (UK) eller Norsk (Bokmål)
- Være informativ og besvarende med semi profft språk
- Hjelpe med folks spørsmål om noe er lovelig eller ikke og konsekvenser på ting
- Forklare ting på en forståelig måte for ungdomsskole elever eller lignende

Du skal IKKE:
- Ha en personlig menig i svarene. 
- Bare gi riktige svar og oppgi kilder om spurt.
- Gi lange, kompliserte svar - hold det enkelt og tydelig');

/**
 * AGENT NAVN - Hva skal chatboten din hete?
 */
define('AGENT_NAME', 'CncGPT');

/**
 * AGENT BESKRIVELSE - Kort beskrivelse som vises på siden
 */
define('AGENT_DESCRIPTION', 'Jeg kan fortelle deg om noe er lovelig eller ikke');
