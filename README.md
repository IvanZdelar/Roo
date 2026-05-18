<h1>Projekt za TVZ mc<sup>2</sup> - tim Koncept</h1>

# Roo aplikacija

Roo je web aplikacija za planiranje putovanja i avantura razvijena pomoću PHP-a bez frameworka.
Korisnicima omogućuje kreiranje personaliziranih avantura, odabira aktivnosti, smještaja, te i mogućnost povezivanja s drugim ljubiteljima putovanja.

# Funkcionalnosti

## Sustav autentifikacije korisnika

Roo App implementira siguran i moderan sustav autentifikacije korisnika koji uključuje:

- Registraciju korisnika
- Prijavu korisnika
- Verifikaciju email adrese
- Reset lozinke putem emaila
- Remember Me funkcionalnost
- Rate limiting zaštitu prijave
- Hashiranje lozinki pomoću `password_hash()`
- Sigurno hashiranje tokena
- Pregled jačine lozinke tijekom registracije

## Personalizacija korisničkog profila

Korisnici mogu potpuno prilagoditi svoj profil:

- Upload profilne slike
- Dodavanje biografije
- Promjena korisničkog imena
- Odabir preferencija putovanja
- Odabir načina na koji vole putovati
- Pregled vlastitog Roo avatara
- Pregled osobnih znački i postignuća

## Kreiranje avantura

Aplikacija omogućuje napredno planiranje putovanja kroz interaktivni multi-step wizard sustav:

- Dodavanje više lokacija
- Potpuna sloboda organizacije putovanja
- Odabir:
  - budžeta
  - smještaja
  - prijevoza
  - aktivnosti
  - tipa putovanja
- Dinamički pregled sažetka avanture
- Upload naslovne slike avanture
- Spremanje avantura u bazu podataka
- Dinamički prijedlozi aktivnosti i smještaja

## Profil korisnika

Svaki korisnik ima vlastiti profil s detaljnom statistikom:

- Broj kreiranih putovanja
- Broj prijeđenih kilometara
- Broj prijatelja i pridruživanja
- Pregled spremljenih avantura
- Pregled objavljenih avantura
- Pregled osobnih znački
- Roo avatar sustav

## Aktivna i završena putovanja

Korisnici mogu pregledavati:

### Aktivna putovanja
- Putovanja drugih korisnika
- Otvorene avanture za zajedničko putovanje

### Završena putovanja
- Arhivu završenih avantura
- Inspiraciju za buduća putovanja
- Pregled iskustava drugih korisnika


## Rang liste i motivacija

Aplikacija uključuje sustav rang lista i motivacije korisnika:

- Rang lista najaktivnijih putnika
- Motivacija za češće korištenje aplikacije
- Sustav znački i postignuća
- Napredak korisnika kroz aplikaciju

# Korištene tehnologije

## Backend
- PHP 8+
- MySQL
- PDO
- Composer
- PHPMailer
- vlucas/phpdotenv

## Frontend
- HTML5
- CSS3
- JavaScript
- Flatpickr

# Sigurnost
Projekt implementira više sigurnosnih mehanizama:

- `password_hash()` i `password_verify()`
- Prepared statements putem PDO-a
- Zaštita od SQL injection napada
- Hashiranje remember me tokena
- `hash_equals()` usporedba tokena
- Rate limiting prijava
- Escape outputa pomoću `htmlspecialchars()`
- SMTP podaci spremljeni u `.env`
- `.gitignore` zaštita osjetljivih podataka

# Struktura projekta 📁

```txt
roo_app/
│
├── css/
├── js/
├── logs/
├── media/
├── ostalo/
├── uploads/
├── vendor/
│
├── adventure-details.php
├── auth_helpers.php
├── bootstrap.php
├── config.php
├── create-adventure.php
├── dashboard.php
├── db.php
├── forgot-password.php
├── get-accommodations.php
├── get-city-activities.php
├── index.php
├── kviz.php
├── logout.php
├── mail_helpers.php
├── nav.php
├── profil.php
├── putovanja.php
├── razgovori.php
├── resend-verification.php
├── reset-password.php
├── upoznavanje.php
├── verify-notice.php
├── verify.php
│
├── .env
├── .env.example
├── .gitignore
├── composer.json
├── composer.lock
└── README.md
```

# Instalacija

## 1. Kloniranje repozitorija

```bash
git clone https://github.com/USERNAME/roo_app.git
```

## 2. Ulazak u projekt

```bash
cd roo_app
```

---

## 3. Instalacija Composer dependencyja

```bash
composer install
```

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

## 5. Pokretanje baze podataka

Importati SQL datoteku:

```
ostalo/database.sql
```

u phpMyAdmin ili MySQL.

## 6. Pokretanje aplikacije

Pokrenuti:
- Apache
- MySQL

u XAMPP-u.

Aplikacija se pokreće na:

```txt
http://localhost/roo_app
```

# Composer paketi

Projekt koristi:

- phpmailer/phpmailer
- vlucas/phpdotenv

# Git i verzioniranje

Projekt koristi:
- Git
- GitHub
- `.gitignore`
- Composer dependency management

# Buduća poboljšanja

- MVC arhitektura
- Admin panel
- API integracija za putovanja
- Chat između korisnika
- Napredni recommendation sustav
- Real-time notifikacije

# Autor

Tim Koncept - Selena Petrinjac, Mihael Kulić i Ivan Zdelar

Tehničko Veleučilište u Zagrebu - Smjer Informatika

2026.
