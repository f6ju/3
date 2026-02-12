# 🤖 Katta AI Chat

En enkel AI-chat for elever ved Hamar Katedralskole. Lær hvordan du kan lage din egen AI-agent!

## 🚀 Kom i gang

1. Last opp filene til ditt webhotell
2. Åpne `config.php` og tilpass innstillingene
3. Besøk nettsiden din og begynn å chatte!

## 📁 Filstruktur

```
api.katta-it.no/
├── index.php       # Hovedsiden med chat-grensesnitt
├── config.php      # ⭐ VIKTIG: Her endrer du innstillingene!
├── api/
│   └── chat.php    # Backend som snakker med OpenAI
├── css/
│   └── style.css   # Styling (kan tilpasses)
└── README.md       # Denne filen
```

## ⚙️ Tilpasse din AI-agent

Åpne `config.php` og endre følgende:

### 1. Agentens navn og beskrivelse
```php
define('AGENT_NAME', 'MatteBot');
define('AGENT_DESCRIPTION', 'Din personlige mattelærer');
```

### 2. System Prompt (Viktigst!)
Dette bestemmer hvordan AI-en oppfører seg:

```php
define('SYSTEM_PROMPT', 'Du er en matematikklærer som hjelper elever 
med algebra og geometri. Forklar alltid steg for steg.');
```

### 3. Temperatur
- `0.0` = Mer presis og konsistent
- `1.0` = Mer kreativ og variert

```php
define('OPENAI_TEMPERATURE', 0.5);
```

## 💡 Eksempler på AI-agenter

### Mattetutor
```php
define('SYSTEM_PROMPT', 'Du er en tålmodig mattelærer. 
- Forklar alltid steg for steg
- Bruk enkle eksempler
- Gi elevene hint i stedet for fasit
- Ros elevene når de gjør fremskritt');
```

### Historieekspert
```php
define('SYSTEM_PROMPT', 'Du er en historiker som elsker å fortelle 
spennende historier. Svar alltid med interessante fakta og 
sammenhenger. Gjør historien levende!');
```

### Programmerings-hjelper
```php
define('SYSTEM_PROMPT', 'Du er en programmerings-mentor for nybegynnere.
- Forklar kode på en enkel måte
- Gi eksempler i Python eller JavaScript
- Hjelp med debugging
- Aldri skriv hele programmer, hjelp eleven forstå');
```

### Skrivehjelp
```php
define('SYSTEM_PROMPT', 'Du er en norsklektor som hjelper med skriving.
- Gi tips om tekststruktur
- Hjelp med ordvalg og setningsbygging
- Gi konstruktiv tilbakemelding
- Aldri skriv tekster for eleven');
```

## 🔒 Sikkerhet

- **API-nøkkelen** i `config.php` skal holdes hemmelig
- Ikke del den med andre eller legg den på GitHub
- Nøkkelen har en kostnad per bruk, så bruk den ansvarlig

## 🎨 Tilpasse designet

Åpne `css/style.css` og endre fargene:

```css
:root {
    --primary: #6366f1;        /* Hovedfarge */
    --bg-main: #0f172a;        /* Bakgrunnsfarge */
    --text-primary: #f1f5f9;   /* Tekstfarge */
}
```

## ❓ Vanlige problemer

### "Kunne ikke koble til serveren"
- Sjekk at API-nøkkelen er riktig i `config.php`
- Sjekk at PHP har `curl` aktivert på serveren

### Ingen respons fra AI
- Sjekk at `api/chat.php` er lastet opp
- Se i nettleserens utviklerverktøy (F12) for feilmeldinger

## 📚 Lær mer

- [OpenAI Dokumentasjon](https://platform.openai.com/docs)
- [PHP cURL](https://www.php.net/manual/en/book.curl.php)

---

Laget for elever ved **Hamar Katedralskole** 🎓
