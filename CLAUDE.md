Laererliv.no — WordPress-tema
Teknisk oversikt

Standalone WordPress-tema (ingen Kadence/Astra-base)
PHP/CSS, ingen byggverktøy eller npm
Deployed på one.com
Repo: https://github.com/barx10/laererliv-theme

Filstruktur
laererliv-theme/
├── assets/js/main.js
├── page-templates/        # Undersidesmaler
├── template-parts/        # Gjenbrukbare delmal-filer
├── 404.php
├── archive.php
├── footer.php
├── functions.php
├── header.php
├── index.php
├── page.php
├── search.php
├── single.php
└── style.css
Design

Fonter: Playfair Display (serif/overskrifter) + Outfit (brødtekst, 300/400/500)
Fargevariabler i :root (ikke endre navn):

--ink: #0E0E0C
--paper: #F7F5F0
--cream: #EDE9E1
--forest: #1E3A2F
--gold: #B8965A
--muted: #7A7670
--border: #D8D4CC


Header: fast, 60px høy, z-index 100
Footer: mørk grønn (--forest)

Custom Post Types

nedlastning — meta: _nedlastning_filtype, _nedlastning_filstr, _nedlastning_fil_url, _nedlastning_aar
app — meta: _app_url, _app_emoji
publikasjon — meta: _pub_url, _pub_dato

Taksonomier

nedlastning_kategori → nedlastning
app_kategori → app
pub_kilde → publikasjon

Hjelpefunksjoner

laererliv_norsk_dato($post_id) — "22. februar 2026"
laererliv_kort_dato($post_id) — "22. feb 2026"
laererliv_lesetid($post_id) — "X min lesetid"
Laererliv_Nav_Walker — flat nav-walker uten dropdowns

Kjente problemer (arbeid pågår)

Undersider (om, nedlastninger, apper, publikasjoner) viser ikke riktig layout
page.php er minimalistisk og arver ikke alle seksjonsklasser
CSS-endringer på undersider slår ikke igjennom som forventet

Prioriteringer nå

Bugfikse undersidenes layout og styling
Koble page-templates til korrekte CSS-klasser fra style.css
Verifisere at CPT-metaboxer lagrer og vises korrekt

Arbeidsstil

Én fil om gangen
Forklar kort hva som endres og hvorfor
Ikke introduser nye avhengigheter
Behold alle CSS-klassenavn og variabelnavn som de er
Bruk eksisterende hjelpefunksjoner fremfor nye
