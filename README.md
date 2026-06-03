# Roo aplikacija

# Projekt za TVZ mc² — tim Koncept

Roo je web aplikacija za planiranje putovanja i avantura razvijena pomoću PHP-a bez frameworka.
Aplikacija korisnicima omogućuje kreiranje personaliziranih avantura, organizaciju putovanja, povezivanje s drugim korisnicima i dijeljenje iskustava nakon završenih putovanja.

---

# Funkcionalnosti

## Sustav autentifikacije korisnika

Roo implementira siguran sustav autentifikacije koji uključuje:

- Registraciju korisnika
- Prijavu korisnika
- Verifikaciju email adrese
- Reset lozinke putem emaila
- Remember Me funkcionalnost
- Rate limiting zaštitu prijava
- Hashiranje lozinki pomoću `password_hash()`
- Sigurno hashiranje tokena
- Pregled jačine lozinke tijekom registracije
- Sigurno spremanje SMTP i DB podataka putem `.env` datoteka

---

## Personalizacija korisničkog profila

Korisnici mogu prilagoditi svoj profil:

- Upload profilne slike
- Dodavanje biografije
- Promjena korisničkog imena
- Odabir preferencija putovanja
- Odabir načina putovanja
- Roo avatar sustav
- Osobne značke i postignuća
- Statistike korisnika

Dinamičke statistike uključuju:

- Broj završenih putovanja
- Broj pridruživanja avanturama
- Broj prijatelja
- Ukupno prijeđenih kilometara

---

## Kreiranje avantura

Aplikacija omogućuje napredno planiranje putovanja kroz interaktivni multi-step wizard:

- Dodavanje više lokacija
- Odabir datuma putovanja
- Odabir budžeta
- Odabir smještaja
- Odabir prijevoza
- Odabir aktivnosti
- Dinamički prijedlozi aktivnosti i smještaja
- Upload naslovne slike avanture
- Dinamički pregled sažetka avanture
- Spremanje avanture u bazu podataka

---

## Aktivnosti po gradovima

Projekt koristi veliku bazu stvarnih aktivnosti i 19 lokacija u Hrvatskoj(19 najvećih gradova u HR).

Svaki grad sadrži:

- Popularne restorane
- Muzeje
- Klubove
- Rooftop barove
- Hidden gem lokacije
- Povijesne znamenitosti
- Outdoor aktivnosti
- Plaže
- Wellness lokacije
- Lokalna događanja

Aktivnosti su podijeljene prema:

- Tipu putovanja
- Budžetu (`low`, `mid`, `high`)
- Lokaciji

---

## Sustav prijatelja i sudionika

Projekt implementira:

### Friend system
- Slanje zahtjeva za prijateljstvo
- Prihvaćanje zahtjeva
- Pregled prijatelja
- Dinamički broj prijatelja na profilu

### Adventure participant system
- Pridruživanje avanturama
- Pregled sudionika avanture
- Praćenje pridruživanja korisnika
- Dinamička statistika sudjelovanja

---

## Završene avanture i galerija

Nakon završetka avanture korisnici mogu:

- Označiti avanturu kao završenu
- Objaviti post o putovanju
- Uploadati slike putovanja
- Dodati opis iskustva
- Prikazati avanturu u galeriji profila

Galerija funkcionira kao mini travel social feed.

---

## Rang liste i motivacija

Aplikacija uključuje:

- Rang listu najaktivnijih korisnika
- Sustav znački
- Travel title sustav
- Motivaciju za aktivno korištenje aplikacije

---

## Osobni ai chatbot

Osobni ai chatbot integriran u sklop aplikacije:

- Služi kao pomoć snalaženja u aplikaciji
- Pomoć kod kreiranja avantura
- Sugestije za planiranje putovanja

---

# Korištene tehnologije

## Backend

- PHP 8+
- MySQL
- PDO
- Composer
- PHPUnit
- PHPMailer
- vlucas/phpdotenv

## Frontend

- HTML5
- CSS3
- JavaScript
- Bootstrap 5
- Flatpickr

---

# Sigurnost

Projekt implementira više sigurnosnih mehanizama:

- `password_hash()` i `password_verify()`
- Prepared statements putem PDO-a
- Zaštita od SQL injection napada
- Hashiranje remember me tokena
- `hash_equals()` usporedba tokena
- Rate limiting prijava
- Escape outputa pomoću `htmlspecialchars()`
- SMTP i DB podaci spremljeni u `.env`
- `.gitignore` zaštita osjetljivih podataka
- Sigurno spremanje tokena u bazu

---

# Testiranje

Projekt koristi PHPUnit za automatizirano testiranje.

## Implementirani testovi

### Unit testovi
- Password hashing test
- Password verify test

### Integration testovi
- Test konekcije na bazu
- Test postojanja tablica

Pokretanje testova:
- Windows:
```bash
vendor\bin\phpunit
```
- Mac:
```bash
vendor/bin/phpunit
```

---

# Struktura projekta

```txt
roo_app/
│
├── .github/
├── css/
├── js/
├── logs/
├── media/
├── uploads/
├── vendor/
│
├── database/
│   ├── schema.sql
│   └── seed.sql
│
├── tests/
│   ├── Unit/
│   └── Integration/
│
├── adventure-details.php
├── auth_helpers.php
├── bootstrap.php
├── complete-adventure.php
├── create-adventure.php
├── dashboard.php
├── db.php
├── footer.php
├── forgot-password.php
├── get-accommodations.php
├── get-city-activities.php
├── handle-notification.php
├── index.php
├── kviz.php
├── logout.php
├── mail_helpers.php
├── nav.php
├── notification_helper.php
├── notifications.php
├── profil.php
├── putovanja.php
├── razgovori.php
├── reject-notification.php
├── resend-verification.php
├── reset-password.php
├── upoznavanje.php
├── verify-notice.php
├── verify.php
│
├── phpunit.xml
├── .env
├── .env.example
├── .gitignore
├── composer.json
├── composer.lock
└── README.md
```

---

# Arhitektura projekta

Projekt koristi modularni pristup bez frameworka.

Ključne komponente:

- `auth_helpers.php` — autentifikacijska logika
- `mail_helpers.php` — email funkcionalnosti
- `db.php` — PDO database layer
- `bootstrap.php` — inicijalizacija aplikacije
- `config.php` — environment konfiguracija

Database konekcija koristi PDO dependency pristup:

```php
$pdo = require 'db.php';
```

što omogućuje:

- jednostavnije testiranje
- modularnost
- bolju održivost
- lakšu integraciju PHPUnit testova

---

# Instalacija

## 1. Kloniranje repozitorija

```bash
git clone https://github.com/USERNAME/roo_app.git
```

## 2. Ulazak u projekt

```bash
cd roo_app
```

## 3. Instalacija dependencyja

```bash
composer install
```

---

## 4. Kreiranje `.env` datoteke

Kopirati `.env.example` u `.env`

Primjer:

```env
APP_ENV=development

DB_HOST=localhost
DB_NAME=roo_app
DB_USER=root
DB_PASS=

MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM=noreply@rooapp.com
MAIL_FROM_NAME=Roo

APP_URL=http://localhost/roo_app
```

---

## 5. Pokretanje baze podataka

Importati:

```txt
database/database.sql
database/seed.sql
```

u phpMyAdmin ili MySQL.

---

## 6. Pokretanje aplikacije

Pokrenuti:

- Apache
- MySQL

u XAMPP-u.

Aplikacija se pokreće na:

```txt
http://localhost/roo_app
```

---

# Composer paketi

Projekt koristi:

- `phpmailer/phpmailer`
- `vlucas/phpdotenv`
- `phpunit/phpunit`

---

# Git i verzioniranje

Projekt koristi:

- Git
- GitHub
- `.gitignore`
- Composer dependency management
- Feature branch development

---

# Buduća poboljšanja

- Migracija na MVC arhitekturu
- Real-time chat
- Push notifikacije
- Mobile responsive improvements
- Smještaj kod lokalaca
- Raspodjela na free i premium verzije aplikacije
- Objavljivanje avantura
- Više gradova i mjesta


---

# Tim i mentorstvo

## Autori
- Selena Petrinjac
- Mihael Kulić
- Ivan Zdelar

## Mentori Mews
- Lucija Jakšić
- Danijel Bešlić

Tehničko Veleučilište u Zagrebu — Informatika

2026.

