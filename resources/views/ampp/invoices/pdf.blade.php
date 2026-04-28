<x-layouts.pdf>
    <x-slot name="style">
        <style>
            ol li {
                page-break-inside: avoid;
                margin-top: 5px;
            }
        </style>
    </x-slot>

    <x-slot name="footer">
        <div class="row justify-content-between border-top py-3">
            <div class="col-3">
                <p>
                    <small>
                        <b>BMK Solutions</b><br>
                        Hortelstraat 37 <br>
                        3530 Houthalen <br>
                        Ondernemingsnummer: 0678.407.310 <br>
                        Btw-nummer: BE0678407310
                    </small>
                </p>
            </div>
            <div class="col-3">
                <p>
                    <small>
                        <b>Contactinformatie</b> <br>
                        Kevin Moons <br>
                        0479314374 <br>
                        info@bmksolutions.be <br>
                        www.bmksolutions.be
                    </small>
                </p>
            </div>
            <div class="col-3">
                <p>
                    <small>
                        <b>Betaalgegevens</b> <br>
                        Bank: ING <br>
                        SWIFT / BIC: BBRUBEBB <br>
                        IBAN: BE66 3630 6172 1443
                    </small>
                </p>
            </div>
        </div>
    </x-slot>

    {{-- Page 1 --}}
    <div class="page">
        <div class="row mb-4">
            <div class="col-5">
                <img src="{{ asset('images/logos/bmk_logo.png') }}" class="img-fluid" style="max-height: 300px"
                    alt="BMK logo">

                @if (isset($reminder) && $reminder)
                    <h4 class="text-danger mt-3 text-uppercase">Herinnering</h4>
                @endif
            </div>

            <div class="col-7 text-end">
                @if ($invoice->type == \App\Enums\InvoiceType::Debit)
                    <h3>Factuur</h3>

                    <p>
                        <small>
                            Factuur nr.: {{ $invoice->custom_name }} <br>
                            Factuur datum: {{ $invoice->custom_created_at->format('d/m/Y') }} <br>
                            Vervaldag: {{ $invoice->expiration_date->format('d/m/Y') }} <br>
                            @if($invoice->po_number)
                                PO nummer: {{ $invoice->po_number }} <br>
                            @endif
                        </small>
                    </p>
                @endif

                @if ($invoice->type == \App\Enums\InvoiceType::Credit)
                    <h3>Creditnota</h3>

                    <p>
                        <small>
                            Creditnota nr.: {{ $invoice->custom_name }} <br>
                            Creditnota datum: {{ $invoice->custom_created_at->format('d/m/Y') }} <br>
                            Vervaldag: {{ $invoice->expiration_date->format('d/m/Y') }} <br>
                            @if($invoice->po_number)
                                PO nummer: {{ $invoice->po_number }} <br>
                            @endif
                        </small>
                    </p>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-7">
                <p>
                    <b>BMK Solutions</b> <br>
                    Kevin Moons <br>
                    Hortelstraat 37 <br>
                    3530 Houthalen <br>
                    0479314374 <br>
                    info@bmksolutions.be <br>
                    www.bmksolutions.be
                </p>
            </div>

            <div class="col-5">
                <p>
                    @if ($invoice->client->company)
                        <b>{{ $invoice->client->company }}</b> <br>
                    @endif

                    {{ $invoice->client->full_name_backwards }} <br>

                    {{ $invoice->client->street }} {{ $invoice->client->number }} <br>
                    {{ $invoice->client->postal }} {{ $invoice->client->city }} <br>

                    @if ($invoice->client->phone)
                        {{ $invoice->client->phone }} <br>
                    @endif

                    {{ $invoice->client->email }} <br>

                    @if ($invoice->client->vat)
                        Btw-nummer: {{ $invoice->client->vat }} <br>
                    @endif
                </p>
            </div>
        </div>

        {{-- Extra info --}}
        <div class="mt-4">
            @if ($invoice->pdf_comment)
                {!! $invoice->pdf_comment !!}
            @endif
        </div>

        {{-- Invoice contents --}}
        <table class="table table-striped table-sm mt-5 mb-0">
            <thead>
                <tr>
                    <th>Beschrijving</th>
                    <th>Eenheidsprijs</th>
                    <th>Aantal</th>
                    <th>Btw</th>
                    <th>Totaal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($lines as $line)
                    @if ($line['type'] == 'header')
                        <tr>
                            <td colspan="5">{{ $line['text'] }}</td>
                        </tr>
                    @else
                        <tr>
                            <!-- Beschrijving -->
                            <td>
                                @if ($line['type'] == 'text')
                                    <div class="row">
                                        <div class="col">
                                            {{ $line['text'] }}
                                        </div>
                                    </div>
                                @endif

                                @if ($line['description'] != null)
                                    <div class="row">
                                        <div class="col">
                                            <small>{!! nl2br($line['description']) !!}</small>
                                        </div>
                                    </div>
                                @endif
                            </td>

                            <!-- Eenheidsprijs -->
                            <td style="white-space: nowrap">
                                € {{ number_format($line['price'], 2, '.', ' ') }}

                                <!--Korting-->
                                @if ($line['discount'] != null && $line['discount'] != 0)
                                    <div class="row">
                                        <div class="col small">
                                            {{ '-' . $line['discount'] . '% Korting' }}
                                        </div>
                                    </div>
                                @endif
                            </td>

                            <!-- Aantal -->
                            <td style="white-space: nowrap">{{ floatval($line['amount']) }}</td>

                            <!-- Btw -->
                            <td style="white-space: nowrap">{{ floatval($line['vat']) . ' %' }}</td>

                            <!-- Totaal -->
                            <td style="white-space: nowrap">€ {{ number_format($line['subtotal'], 2, '.', ' ') }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        <hr class="mb-3" style="border: none; height: 3px; color: darkgray; background-color: darkgray;">

        {{-- Total amounts --}}
        <div class="row justify-content-end mb-4">
            <div class="col-6">
                <div class="d-flex justify-content-between">
                    <p class="my-0 py-0" style="white-space: nowrap;">Bedrag vóór belasting</p>
                    <p class="my-1 py-0">€ {{ number_format($invoice->amount, 2, '.', ' ') }}</p>
                </div>

                <div class="d-flex justify-content-between">
                    <p class="my-0 py-0 mb-1">Btw</p>
                    <p class="my-0 py-0">
                        @php
                            $totalBtw = 0;
                            foreach ($lines as $line) {
                                if ($line['type'] == 'text') {
                                    $totalBtw += $line['subtotal'] * ($line['vat'] / 100);
                                }
                            }
                            
                            echo '€ ' . number_format($totalBtw, 2, '.', ' ');
                        @endphp
                    </p>
                </div>

                <div class="d-flex justify-content-between border-top mt-2 pt-1">
                    <p style="white-space: nowrap;"><b>Totaal verschuldigd bedrag</b></p>
                    <p>€ {{ number_format($invoice->amount_with_vat, 2, '.', ' ') }}</p>
                </div>
            </div>
        </div>

        {{-- Invoice info --}}
        <div class="row">
            <div class="col-8">
                @if ($invoice->type == \App\Enums\InvoiceType::Debit)
                    <div class="d-flex justify-content-between mb-1">
                        <b>Gestructureerde mededeling:</b>
                        <span class="text-end">{{ $invoice->ogm }}</span>
                    </div>
                @endif

                <div class="d-flex justify-content-between mb-1">
                    <b>Betalingstermijn:</b>
                    <span
                        class="text-end">{{ $invoice->expiration_date->diffInDays($invoice->custom_created_at) + 1 }}</span>
                </div>

                <div class="d-flex justify-content-between mb-1">
                    <b>Vervaldag:</b>
                    <span class="text-end">{{ $invoice->expiration_date->format('d/m/Y') }}</span>
                </div>

                @if ($invoice->type == \App\Enums\InvoiceType::Debit)
                    <div class="d-flex justify-content-between mb-1">
                        <b>Interest bij laattijdige betaling:</b>
                        <span class="text-end">8%</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Page 2 --}}
    <div class="page">
        <div class="row mb-4">
            <div class="col-5">
                <img src="{{ asset('images/logos/bmk_logo.png') }}" class="img-fluid" style="max-height: 300px"
                    alt="BMK logo">
            </div>

            <div class="col-7 text-end">
                <h4 class="text-right"> <b> Algemene Voorwaarden </b></h4>
                <p class="text-right"><small>{{ $invoice->created_at->format('d/m/Y') }}</small></p>
            </div>
        </div>

        {{-- Factuurvoorwaarden --}}
        <div>
            <ol>
                <li>
                    <small>
                        De opdrachtgever aanvaardt deze algemene verkoopsvoorwaarden, zelfs indien er in zijn opdracht
                        of
                        bestelling andere voorwaarden staan.
                    </small>
                </li>
                <li>
                    <small>
                        Prijzen welke door BMK Solutions BV in het kader van offertes worden meegedeeld zijn steeds
                        vrijblijvend,
                        tenzij uitdrukkelijk werd aangegeven dat ze definitief zijn. De definitieve prijzen in offertes
                        opgegeven,
                        kennen slechts een geldingsduur van 30 dagen of tot einde kalendermaand of tot einde
                        promotiedatum.
                        Alle prijzen zijn exclusief BTW.
                        Tarieven door BMK Solutions BV meegedeeld met het oog op dienstverlening zijn vastgelegd in
                        functie
                        van prestaties.
                    </small>
                </li>
                <li>
                    <small>
                        De door BMK Solutions BV opgegeven leveringstermijnen zijn indicatief en niet bindend, tenzij
                        uitdrukkelijk anders overeengekomen werd.
                        De termijnen worden steeds in werkdagen geformuleerd. Vertraging in de levering kan in geen
                        geval
                        recht geven op annulering van een bestelling of een schadevergoeding.
                    </small>
                </li>
                <li>
                    <small>
                        Iedere klacht betredende de levering, van welke aard ook,
                        dient door de klant binnen de 8 werkdagen vanaf de levering der goederen of de begindatum van
                        het
                        presteren van de diensten te worden bekendgemaakt
                        aan BMK Solutions BV en dit per aangetekende brief.
                    </small>
                </li>
                <li>
                    <small>
                        Eventuele klachten over de levering of prestaties kunnen niet als voorwendsel worden
                        aangewend
                        om betaling van facturen op te schorten of te vertragen.
                        Het gebrek aan geschreven protest van een factuur binnen de 7 werkdagen vanaf de verzending
                        ervan,
                        houdt de onherroepelijke aanvaarding van de factuur en de erin vermelde bedragen, producten en
                        diensten in.
                    </small>
                </li>
                <li>
                    <small>
                        Facturen zijn betaalbaar binnen de 30 dagen na factuurdatum, tenzij uitdrukkelijk anders
                        overeengekomen.
                        Indien de factuur op de vervaldag niet is betaald, dan is van rechtswege en zonder enige
                        ingebrekestelling een contractuele intrestverschuldigd van 8 % per maand,
                        waarbij elke aangevangen maand voor een volledige telt.
                        Elke laattijdige betaling door de klant geeft BMK Solutions tevens het recht om een
                        administratiekost
                        van 250 EURO aan te rekenen.
                        Alle kosten als gevolg van het afdwingen van betaling via gerechtelijke weg, inclusief
                        advocatenkosten, zullen worden gedragen door de klant.
                    </small>
                </li>
                <li>
                    <small>
                        Wijzigingen van contactgegevens zoals adressen, telefoonnummers en e-mailadressen van de klant
                        moeten
                        tijdig door de klant aan BMK Solutions BV worden meegedeeld.
                    </small>
                </li>
                <li>
                    <small>
                        Alle rechten van intellectuele eigendom ter zake van de producten en/of diensten als mede de
                        ontwerpen, programmatuur,
                        documentatie en alle andere materialen die worden ontwikkeld en/of gebruikt ter voorbereiding
                        of uitvoering van de overeenkomst tussen BMK Solutions BV en de klant,
                        dan wel die daaruit voortvloeien, berusten uitsluitend bij BMK Solutions BV of diens
                        leveranciers.
                        De levering van producten en/of diensten strekt niet tot enige overdracht van de rechten van
                        intellectuele eigendom.
                    </small>
                </li>
                <li>
                    <small>
                        De klant zal niet zonder voorafgaande schriftelijke toestemming van BMK Solutions BV de
                        producten
                        en
                        resultaten van de
                        diensten op enige wijze geheel of gedeeltelijk openbaar maken, verveelvoudigen of aan een derde
                        beschikbaar stellen tenzij anders overeengekomen.
                    </small>
                </li>
                <li>
                    <small>
                        De klant zal aanduidingen van BMK Solutions BV of haar leveranciers betreffende auteursrechten,
                        merken,
                        handelsnamen of andere rechten van intellectuele eigendom niet verwijderen of wijzigen.
                    </small>
                </li>
                <li>
                    <small>
                        BMK Solutions BV behoudt zich het recht voor elk van deze voorwaarden en bepalingen op eender
                        welk
                        moment eenzijdig te wijzigen.
                    </small>
                </li>
                <li>
                    <small>
                        Op alle overeenkomsten gesloten met BMK Solutions BV is het Belgisch recht van toepassing.
                        Elk geschil dat in verband staat met de overeenkomsten gesloten met BMK Solutions BV zal
                        exclusief
                        worden behandeld door de rechtbanken te Hasselt.
                    </small>
                </li>
                <li>
                    <small>Deze algemene voorwaarden zijn de enige geldend voor de beide partijen.</small>
                </li>
                <li>
                    <small>Goederen worden geleverd zoals omschreven in de factuur of bestelbon.</small>
                </li>
                <li>
                    <small>
                        Voor wat betreft eventuele gebreken aan de geleverde goederen wordt toepassing gemaakt van de
                        wet van 1 september 2004 betreffende de
                        bescherming van de consument bij verkoop van consumptiegoederen.
                    </small>
                </li>
                <li>
                    <small>
                        De geleverde goederen, website, intranet, extranet, software, webshop,...
                        blijven eigendom van BMK Solutions BV zolang de volledige prijs (hoofdsom, kosten en intresten)
                        niet
                        betaald is.
                    </small>
                </li>
                <li>
                    <small>De verkrijger draagt de risico’s vanaf de levering. Deze moet de goederen in hun staat
                        bewaren.</small>
                </li>
                <li>
                    <small>
                        De opdrachtgever is verantwoordelijk voor de juistheid van de teksten en/of afbeeldingen welke
                        hij opgeeft of aanvaardt, evenals voor de inhoud,
                        de titels, keuze van illustraties, media, enz., en verbindt er zich daarenboven toe BMK
                        Solutions
                        BV
                        zonder voorbehoud te vrijwaren voor elke eis die door derden zou worden ingeleid met betrekking
                        tot de gepubliceerde teksten en/of illustraties.
                        Hij erkent de teksten en illustraties opgenomen in elk door hem gekozen en goedgekeurde
                        publiciteitsvorm.
                    </small>
                </li>
                <li>
                    <small>
                        De broncode en database blijven het exclusieve eigendom van BMK Solutions BV of haar
                        licentiegever,
                        naargelang het geval.
                        De broncode en de database mogen niet gekopieerd, gereproduceerd, opnieuw gepubliceerd,
                        gedownload, openbaar gemaakt,
                        uitgezonden of overgedragen worden zonder uitdrukkelijke toestemming van BMK Solutions BV.
                    </small>
                </li>
                <li>
                    <small>De aangeleverde teksten en/of illustraties blijven eigendom van de klant.</small>
                </li>
                <li>
                    <small>
                        Een voorschot kan bepaald worden bij ondertekening van de offerte. BMK Solutions BV heeft het
                        recht
                        het voorschotbedrag vrij te bepalen.
                        Dit voorschot kan onder geen enkel beding worden teruggevorderd.
                    </small>
                </li>
                <li>
                    <small>
                        Tenzij anders werd overeengekomen bij de bestelling/opdracht worden de specifieke overeenkomsten
                        voor de duur van één (1) jaar aangegaan.
                        Daarna worden zij automatisch hernieuwd voor een opeenvolgende termijn van één (1) jaar,
                        tenzij voor zover één van de partijen een schriftelijke kennisgeving van ten minste één (1)
                        maand voor de afloop van termijn verstuurt,
                        waarin zij haar wil te kennen geeft om de specifieke overeenkomst niet te verlengen.
                    </small>
                </li>
                <li>
                    <small>
                        De registratie van domeinnamen geschiedt volgens de reglementen van de relevante
                        verantwoordelijke voor de registratie van domeinnamen
                        (zoals de VZW DNS Belgium). De klant heeft kennis genomen van en aanvaardt deze algemene
                        voorwaarden voor de domeinnaam registratie,
                        en verklaart dat BMK Solutions BV hem heeft geïnformeerd aangaande deze algemene voorwaarden.
                        BMK Solutions BV draagt op geen enkele wijze enige aansprakelijkheid voor het ter kwader trouw
                        registreren van domeinnamen door derden,
                        het registreren van domeinnamen op verzoek van de klant welke een inbreuk zouden uitmaken op
                        rechten van derden, enz.
                    </small>
                </li>
                <li>
                    <small>
                        De klant zal BMK Solutions BV telkens vrijwaren voor eventuele aanspraken van derden ingevolge
                        de
                        registratie van een domeinnaam.
                    </small>
                </li>
                <li>
                    <small>
                        De klant mag op geen enkele wijze van de aangeboden diensten of faciliteiten, daaronder begrepen
                        de aangeboden opslagruimten,
                        aanwenden tot het plegen van inbreuken, het veroorzaken van schade of hinder ten aanzien van
                        EMKInvest BV of derden.
                        De activiteiten van de klant mogen evenmin aanleiding daartoe geven. De klant verzekert te
                        allentijde dat geen strafbare en/of inbreuk makende gegevens,
                        bestanden, programmatuur, meta-tags, hyperlinks, deeplinks of vergelijkbare verwijzingen of
                        informatie aanwezig zijn op apparatuur ter beschikking gesteld
                        door BMK Solutions BV. Op eerste verzoek van BMK Solutions BV zal de klant BMK Solutions BV
                        hiervoor
                        vrijwaren (inclusief voor advocatenkosten) en op
                        zijn eigen kosten tussenkomen in elke procedure ingesteld tegen BMK Solutions BV die hiermee
                        verband houdt.
                        Spamming is op al de door BMK Solutions BV ter beschikking gestelde apparatuur en systemen
                        streng
                        verboden en lijdt tot directe afsluiting.
                        De klant verzekert onmiddellijk gevolg te geven aan elk verzoek van BMK Solutions BV alsook aan
                        elk
                        redelijk verzoek van een derde tot verwijdering
                        en/of aanpassing van eigen inhoud. De klant staat zijn recht af om enige schadevergoeding van
                        BMK Solutions BV te claimen.
                        Zo laat BMK Solutions BV bij wijze van voorbeeld op haar servers geen pornografisch materiaal
                        toe,
                        evenmin als illegale MP3-sites of sites van waarop zaken worden aangeboden welke strijdig zijn
                        met de openbare orde of de goede zeden,
                        dan wel welke een onrechtmatige praktijk uitmaken, evenmin mogen van op servers van BMK
                        Solutions
                        BV activiteiten worden uitgeoefend welke
                        inbreuken plegen op beschermde werken of welke strafbaar zijn gesteld op de Wet op de
                        Informaticacriminaliteit.
                    </small>
                </li>
                <li>
                    <small>
                        In geen geval is BMK Solutions BV aansprakelijk voor onrechtstreekse schade zoals o.a.
                        commerciële
                        of financiële verliezen,
                        verlies van data, verlies van reputatie, winst of omzetderving, verlies van klanten en verliezen
                        als gevolg van gerechtelijke stappen door
                        derden ondernomen tegen de klant. BMK Solutions BV kan zo op geen enkele wijze aansprakelijk
                        worden
                        gesteld voor eventuele uitval van de
                        internetconnectie door technische of andere storingen. De klant is als enige aansprakelijk voor
                        het behoorlijk gebruik van het product,
                        de dienst of de software daarbij rekening houdend met de specificaties, de documentatie en de
                        instructies van BMK Solutions BV.
                    </small>
                </li>
            </ol>
        </div>
    </div>
</x-layouts.pdf>
