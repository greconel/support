# AMPP BMK Solutions
Deze AMPP is intern voor BMK zelf. Wij gebruiken dit voor:
- Klantenbeheer
- Projectmanagement
- Tijdregistraties
- Facturatie
- Connecties

### Klantenbeheer
Onze AMPP heeft een basis klantenbeheer.
Een klant kan ook meerdere contactenpersonen bevatten.

Er is ook een leads bord. Hier komen alle leads (potentiële klanten) in en worden hier ook
in opgevolgd. De kolommen zijn volledig aanpasbaar.

### Projectmanagement
Alle projecten worden hier beheerd via projectbeschrijving, leden, to do's, bestanden, emails, ....
Er zijn ook enkele voorgedefinieerde categorieën.

Projecten hangen onder klanten om een duidelijk overzicht te hebben welke projecten onder 
welke klant hangen.

Rapporten..

### Tijdregistraties
Om de klanten juist te kunnen factureren zijn er tijdregistraties aanwezig.
Elke gebruiker buiten de admin ziet enkel zijn eigen tijdregistraties.
Een tijdregistratie kan maar hoeft niet noodzakelijk aan een project te hangen.

### Facturatie
Alle facturatie van BMK gebeurd via AMPP.
Clearfacts..
Offertes..
Betalingen..
Rapporten..
Leveranciers..

## Project setup
1. Clone the Repository:
    ```bash
    git clone [repository-link]
    ```

2. Install Dependencies:-
    ```bash
    cd [project-name]
    composer install
    npm install
    ```

3. Configure Environment:
    ```bash
    cp .env.example .env
    ```
   Update `.env` with your database and other configurations.

4. Run Migrations and seed:
    ```bash
    php artisan migrate --seed
    ```

5. Set application key:
    ```bash
    php artisan key:generate
    ```

6. Generate css and js
    ```bash
    npm run dev
    ```

### Environment variables
Clearfacts credentials.
```dotenv
CLEARFACTS_URL=https://api.clearfacts.be/graphql
CLEARFACTS_VAT="0678 407 310"
CLEARFACTS_TOKEN=
```

Google maps credentials.
```dotenv
GOOGLE_MAPS_GEOCODING_API_KEY=
GOOGLE_MAPS_API=
```

Teams webhook for notifications. This can be created on a channel
```dotenv
TEAMS_WEBHOOK_URL=
```

## Deployment (Ploi)
Production url: https://ampp.dev/
Database: amppdev
