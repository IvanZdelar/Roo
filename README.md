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
- Osobna postignuća
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

## Rang liste i motivacija

Aplikacija uključuje:

- Rang listu najaktivnijih korisnika
- Travel title sustav
- Motivaciju za aktivno korištenje aplikacije

---

## Osobni AI chatbot

Osobni AI chatbot integriran u sklop aplikacije:

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
- SVG

---

# Sigurnost

Projekt implementira više sigurnosnih mehanizama:

• password_hash() i password_verify() za lozinke
• Prepared statements putem PDO za zaštitu od SQL injection
• htmlspecialchars() za escape outputa (XSS zaštita)
• Rate limiting za prijave (login_attempts tablica)
• hash_equals() za sigurnu usporedbu tokena
• Remember Me tokeni s hashiranjem
• Email verifikacija novih korisnika
• .env datoteka za osjetljive podatke (nije u Gitu)
• .gitignore zaštita — .env, vendor/, uploads/


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
│   ├── database.sql
│   └── seed.sql
│
├── tests/
│   ├── Unit/
│   └── Integration/
│
├── bootstrap.php
├── db.php
├── nav.php
├── chatbot.php
├── auth_helpers.php
├── index.php
├── verify-notice.php
├── verify.php
├── upoznavanje.php
├── kviz.php
├── mail_helpers.php
├── resend-verification.php
├── forgot-password.php
├── reset-password.php
├── logout.php
├── create-adventure.php
├── get-accommodations.php
├── get-city-activities.php
├── adventure-details.php
├── complete-adventure.php
├── save-adventure.php
├── dashboard.php
├── profil.php
├── putovanja.php
├── razgovori.php
├── handle-notification.php
├── notification_helper.php
├── notifications.php
├── mark-notification-read.php
├── reject-notification.php
├── achievement_helper.php
├── achievements.php
├── check-achievements.php
├── mascot_helpers.php
├── customize-mascot.php
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

## 1. Preduvjeti

Prije instalacije potrebno je imati instalirane sljedeće alate:

- PHP - 8.0 ili noviji
- MySQL / MariaDB - 10.4 ili noviji
- Apache - 2.4 ili noviji - XAMPP preporučen
- Composer - 2.x - dependancy manager za PHP
- Git - Za kloniranje repozitorija

PHP ekstenzije - Provjeriti da su aktivirane u php.ini:

- pdo_mysql
- mbstring
- openssl
- fileinfo
- gd


## 2. Kloniranje repozitorija

Ako koristite XAMPP, sve naredbe i raspakiravanje radite unutar vašeg htdocs direktorija(npr. C:\xampp\htdocs)

```bash
git clone https://github.com/IvanZdelar/Roo.git
```

Ako ste preuzeli projekt kao .zip datoteku s GitHub-a, raspakirajte njezin sadržaj izravno u xampp/htdocs direktorij.

## 3. Ulazak u projekt

Ako ste klonirali putem Git-a:

```bash
cd Roo
```

Ako ste preuzeli i raspakirali .zip datoteku:

```bash
cd Roo-main
```

## 4. Instalacija dependencyja

```bash
composer install
```

---

## 5. Kreiranje `.env` datoteke

Kopirati `.env.example` u `.env`

```bash
copy .env.example .env    (Windows)
cp .env.example .env       (Mac/Linux)
```

Primjer:

```env
APP_ENV=development

DB_HOST=localhost
DB_NAME=roo_app
DB_USER=root
DB_PASS=

MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=vaš Mailtrap username
MAIL_PASSWORD=vaš Mailtrap password
MAIL_FROM=noreply@rooapp.com
MAIL_FROM_NAME=Roo

APP_URL=http://localhost/Roo (ili http://localhost/Roo-main ako je projekt pokrenut iz raspakirane zip datoteke)
```

---

## 6. Postavljanje baze podataka

Otvoriti phpMyAdmin na http://localhost/phpmyadmin i importati:

```txt
database/database.sql
database/seed.sql
```

### Test korisnici

Nakon importa seed.sql dostupni su sljedeći test korisnici(Lozinka za sve test korisnike: test1234):

 - test1@test.com
 - test2@test.com
 - test3@test.com
 - test4@test.com
 - test5@test.com 

---

## 7. Postavljanje foldera za uploadove
Kreirati sljedece foldere ako ne postoje:

```bash
mkdir uploads
mkdir uploads/adventures
mkdir uploads/profiles
```

## 8. Pokretanje aplikacije

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
- Real-time chat s WebSocket tehnologijom
- Push notifikacije
- Sustav smještaja kod lokalaca
- Free i premium verzija aplikacije
- Objavljivanje avantura na javnom feedu
- Proširenje baze gradova izvan Hrvatske
- Mobile aplikacija



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

