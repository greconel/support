<div>
    <div class="row">
        <div class="col-12 mb-3">
            <x-forms.label for="name">{{ __('Name') }}</x-forms.label>
            <x-forms.input name="name" wire:model="name" />
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-3">
            <x-forms.label for="notes">{{ __('Notes') }}</x-forms.label>
            <x-forms.textarea name="notes" wire:model="notes"></x-forms.textarea>
        </div>
    </div>

    <hr>

    <p class="fs-3 fw-bolder">{{ __('QR code builder') }}</p>

    {{-- contents --}}
    <div class="row">
        <div class="col-12 mb-3">
            <x-forms.label for="content">{{ __('Contents') }}</x-forms.label>
            <x-forms.textarea name="contents" wire:model.blur="contents" />
        </div>
    </div>

    {{-- general --}}
    <div class="row">
        <div class="col-md-2 mb-3">
            <x-forms.label for="format">{{ __('Format') }}</x-forms.label>
            <x-forms.select name="format" :options="$formats" wire:model="format" />
        </div>

        <div class="col-md-2 mb-3">
            <x-forms.label for="style">{{ __('Style') }}</x-forms.label>
            <x-forms.select name="style" :options="$styles" wire:model="style" />
        </div>

        <div class="col-md-2 mb-3">
            <x-forms.label for="size">{{ __('Size') }}</x-forms.label>
            <x-forms.input type="number" name="size" step="1" wire:model.blur="size" />
        </div>

        <div class="col-md-3 mb-3">
            <x-forms.label for="color">{{ __('Color') }}</x-forms.label>
            <div
                x-data="{ color: '#000000ff' }"
                x-init="
                    picker = new Picker({
                        parent: $refs.button,
                        color,
                        onDone: rawColor => {
                            color = rawColor.hex;

                            const rgba = {
                                r: rawColor.rgba[0],
                                g: rawColor.rgba[1],
                                b: rawColor.rgba[2],
                                a: rawColor.rgba[3] * 100
                            }

                            $wire.set('color', rgba);
                        }
                    });
                "
                wire:ignore
            >
                <button class="btn w-100" x-text="color" :style="`background: ${color} !important`" x-ref="button"></button>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <x-forms.label for="color">{{ __('Background color') }}</x-forms.label>
            <div
                x-data="{ color: '#ffffffff' }"
                x-init="
                    picker = new Picker({
                        parent: $refs.button,
                        color,
                        onDone: rawColor => {
                            color = rawColor.hex;

                            const rgba = {
                                r: rawColor.rgba[0],
                                g: rawColor.rgba[1],
                                b: rawColor.rgba[2],
                                a: rawColor.rgba[3] * 100
                            }

                            $wire.set('backgroundColor', rgba);
                        }
                    });
                "
                wire:ignore
            >
                <button class="btn w-100" x-text="color" :style="`background: ${color} !important`" x-ref="button"></button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2 mb-3">
            <x-forms.label for="error_correction">{{ __('Error correction') }}</x-forms.label>
            <i
                class="fas fa-question-circle"
                data-bs-toggle="tooltip"
                data-bs-placement="top"
                title="The more error correction used; the bigger the QrCode becomes and the less data it can store."
                wire:ignore.self
            ></i>
            <x-forms.select name="error_correction" :options="$errorCorrections" wire:model="errorCorrection" />
        </div>
    </div>

    {{-- gradient --}}
    <p class="lead mt-4 mb-3">{{ __('Gradient') }}</p>

    <div class="row">
        <div class="col-md-4 mb-3">
            <x-forms.label for="gradient_type">{{ __('Gradient type') }}</x-forms.label>
            <x-forms.select name="gradient_type" :options="$gradientTypes" wire:model="gradientType" />
        </div>

        <div class="col-md-4 mb-3">
            <x-forms.label for="color">{{ __('Gradient start color') }}</x-forms.label>
            <div
                x-data="{ color: '#000000' }"
                x-init="
                    picker = new Picker({
                        parent: $refs.button,
                        alpha: false,
                        color,
                        onDone: rawColor => {
                            color = rawColor.hex;

                            const rgb = {
                                r: rawColor.rgba[0],
                                g: rawColor.rgba[1],
                                b: rawColor.rgba[2]
                            }

                            $wire.set('gradientStartColor', rgb);
                        }
                    });
                "
                wire:ignore
            >
                <button class="btn w-100" x-text="color" :style="`background: ${color} !important`" x-ref="button"></button>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <x-forms.label for="color">{{ __('Gradient end color') }}</x-forms.label>
            <div
                x-data="{ color: '#000000' }"
                x-init="
                    picker = new Picker({
                        parent: $refs.button,
                        alpha: false,
                        color,
                        onDone: rawColor => {
                            color = rawColor.hex;

                            const rgb = {
                                r: rawColor.rgba[0],
                                g: rawColor.rgba[1],
                                b: rawColor.rgba[2]
                            }

                            $wire.set('gradientEndColor', rgb);
                        }
                    });
                "
                wire:ignore
            >
                <button class="btn w-100" x-text="color" :style="`background: ${color} !important`" x-ref="button"></button>
            </div>
        </div>
    </div>

    {{-- eyes --}}
    <p class="lead mt-4 mb-3">{{ __('Eyes') }}</p>

    <div class="row">
        <div class="col-md-4 mb-3">
            <x-forms.label for="eye_style">{{ __('Eye style') }}</x-forms.label>
            <x-forms.select name="eye_style" :options="$eyeStyles" wire:model="eyeStyle" />
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <x-forms.label for="color">{{ __('First eye inner color') }}</x-forms.label>
            <div
                x-data="{ color: '#000000' }"
                x-init="
                    picker = new Picker({
                        parent: $refs.button,
                        alpha: false,
                        color,
                        onDone: rawColor => {
                            color = rawColor.hex;

                            const rgb = {
                                r: rawColor.rgba[0],
                                g: rawColor.rgba[1],
                                b: rawColor.rgba[2]
                            }

                            $wire.set('firstEyeInnerColor', rgb);
                        }
                    });
                "
                wire:ignore
            >
                <button class="btn w-100" x-text="color" :style="`background: ${color} !important`" x-ref="button"></button>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <x-forms.label for="color">{{ __('First eye outer color') }}</x-forms.label>
            <div
                x-data="{ color: '#000000' }"
                x-init="
                    picker = new Picker({
                        parent: $refs.button,
                        alpha: false,
                        color,
                        onDone: rawColor => {
                            color = rawColor.hex;

                            const rgb = {
                                r: rawColor.rgba[0],
                                g: rawColor.rgba[1],
                                b: rawColor.rgba[2]
                            }

                            $wire.set('firstEyeOuterColor', rgb);
                        }
                    });
                "
                wire:ignore
            >
                <button class="btn w-100" x-text="color" :style="`background: ${color} !important`" x-ref="button"></button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <x-forms.label for="color">{{ __('Second eye inner color') }}</x-forms.label>
            <div
                x-data="{ color: '#000000' }"
                x-init="
                    picker = new Picker({
                        parent: $refs.button,
                        alpha: false,
                        color,
                        onDone: rawColor => {
                            color = rawColor.hex;

                            const rgb = {
                                r: rawColor.rgba[0],
                                g: rawColor.rgba[1],
                                b: rawColor.rgba[2]
                            }

                            $wire.set('secondEyeInnerColor', rgb);
                        }
                    });
                "
                wire:ignore
            >
                <button class="btn w-100" x-text="color" :style="`background: ${color} !important`" x-ref="button"></button>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <x-forms.label for="color">{{ __('Second eye outer color') }}</x-forms.label>
            <div
                x-data="{ color: '#000000' }"
                x-init="
                    picker = new Picker({
                        parent: $refs.button,
                        alpha: false,
                        color,
                        onDone: rawColor => {
                            color = rawColor.hex;

                            const rgb = {
                                r: rawColor.rgba[0],
                                g: rawColor.rgba[1],
                                b: rawColor.rgba[2]
                            }

                            $wire.set('secondEyeOuterColor', rgb);
                        }
                    });
                "
                wire:ignore
            >
                <button class="btn w-100" x-text="color" :style="`background: ${color} !important`" x-ref="button"></button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <x-forms.label for="color">{{ __('Third eye inner color') }}</x-forms.label>
            <div
                x-data="{ color: '#000000' }"
                x-init="
                    picker = new Picker({
                        parent: $refs.button,
                        alpha: false,
                        color,
                        onDone: rawColor => {
                            color = rawColor.hex;

                            const rgb = {
                                r: rawColor.rgba[0],
                                g: rawColor.rgba[1],
                                b: rawColor.rgba[2]
                            }

                            $wire.set('thirdEyeInnerColor', rgb);
                        }
                    });
                "
                wire:ignore
            >
                <button class="btn w-100" x-text="color" :style="`background: ${color} !important`" x-ref="button"></button>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <x-forms.label for="color">{{ __('Third eye outer color') }}</x-forms.label>
            <div
                x-data="{ color: '#000000' }"
                x-init="
                    picker = new Picker({
                        parent: $refs.button,
                        alpha: false,
                        color,
                        onDone: rawColor => {
                            color = rawColor.hex;

                            const rgb = {
                                r: rawColor.rgba[0],
                                g: rawColor.rgba[1],
                                b: rawColor.rgba[2]
                            }

                            $wire.set('thirdEyeOuterColor', rgb);
                        }
                    });
                "
                wire:ignore
            >
                <button class="btn w-100" x-text="color" :style="`background: ${color} !important`" x-ref="button"></button>
            </div>
        </div>
    </div>

    {{-- image --}}
    <p class="lead mt-4 mb-3">{{ __('Merge image') }}</p>

    <div class="row align-items-end">
        <div class="col-md-4">
            <x-forms.label for="image">{{ __('Image') }}</x-forms.label>
            <x-forms.file name="image" wire:model="image" />
        </div>

        <div class="col-md-4">
            <x-forms.label for="image_size">{{ __('Image size') }}</x-forms.label>
            <x-forms.input type="number" name="image_size" step="1" min="0" wire:model.blur="imageSize" />
        </div>

        <div class="col-md-4">
            <button class="btn btn-danger" wire:click="$set('image', null)">{{ __('Delete image') }}</button>
        </div>
    </div>

    @if($qrCode)
        <p class="lead mt-4 mb-3">{{ __('Output') }}</p>

        <img src="data:image/png;base64, {!! $qrCode !!}" alt="qrCode" class="d-block mx-auto my-4">

        <div class="d-flex justify-content-end">
            <button class="btn btn-primary" wire:click="download">{{ __('Download') }}</button>
            <button class="btn btn-primary ms-2" wire:click="save">{{ __('Save') }}</button>
        </div>
    @endif

    <hr>

    <p class="fs-3 fw-bolder">{{ __('Helpers') }}</p>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Usage</th>
                <th>Prefix</th>
                <th>Example</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Website URL</td>
                <td>http://</td>
                <td><a href="{{ config('app.url') }}">{{ config('app.url') }}</a></td>
            </tr>
            <tr>
                <td>Secured URL</td>
                <td>https://</td>
                <td><a href="{{ config('app.url') }}">{{ config('app.url') }}</a></td>
            </tr>
            <tr>
                <td>E-mail Address</td>
                <td>mailto:</td>
                <td><a href="mailto:info@bmksolutions.be">info@bmksolutions.be</a></td>
            </tr>
            <tr>
                <td>Phone Number</td>
                <td>tel:</td>
                <td>tel:555-555-5555</td>
            </tr>
            <tr>
                <td>Text (SMS)</td>
                <td>sms:</td>
                <td>sms:555-555-5555</td>
            </tr>
            <tr>
                <td>Text (SMS) With Pretyped Message</td>
                <td>sms:</td>
                <td>sms::I am a pretyped message</td>
            </tr>
            <tr>
                <td>Text (SMS) With Pretyped Message and Number</td>
                <td>sms:</td>
                <td>sms:555-555-5555:I am a pretyped message</td>
            </tr>
            <tr>
                <td>Geo Address</td>
                <td>geo:</td>
                <td>geo:-78.400364,-85.916993</td>
            </tr>
            <tr>
                <td>MeCard</td>
                <td>mecard:</td>
                <td>MECARD:BMK Solutions;Hortelstraat 37, Houthalen, 3530;TEL:+32(0) 479 31 43 74;EMAIL:info@bmksolutions.be;</td>
            </tr>
            <tr>
                <td>VCard</td>
                <td>BEGIN:VCARD</td>
                <td>
                    BEGIN:VCARD <br>
                    N:Smith;John; <br>
                    TEL;TYPE=work,VOICE:(111) 555-1212 <br>
                    TEL;TYPE=home,VOICE:(404) 386-1017 <br>
                    TEL;TYPE=fax:(866) 408-1212 <br>
                    EMAIL:smith.j@smithdesigns.com <br>
                    ORG:Smith Designs LLC <br>
                    TITLE:Lead Designer <br>
                    ADR;TYPE=WORK,PREF:;;151 Moore Avenue;Grand Rapids;MI;49503;United States of America <br>
                    URL:https://www.smithdesigns.com <br>
                    VERSION:3.0 <br>
                    END:VCARD <br>
                </td>
            </tr>
            <tr>
                <td>Wifi</td>
                <td>wifi:</td>
                <td>wifi:WEP/WPA;SSID;PSK;Hidden(True/False)</td>
            </tr>
        </tbody>
    </table>

    <p class="fs-3 fw-bolder">{{ __('VCard formats') }}</p>

    <table class="table table-striped table-bordered">
        <tbody>
        <tr>
            <td>
                Parameter
            </td>
            <td>
                Presence
            </td>
            <td>
                Description
            </td>
            <td>
                Format
            </td>
        </tr>
        <tr>
            <td>
                BEGIN
            </td>
            <td>
                Required
            </td>
            <td>
                All vCards must start with this parameter
            </td>
            <td>
                BEGIN:VCARD
            </td>
        </tr>
        <tr>
            <td>
                N
            </td>
            <td>
                Optional
            </td>
            <td>
                Full name
            </td>
            <td>
                N:Smith;John;
            </td>
        </tr>
        <tr>
            <td>
                TEL;TYPE
            </td>
            <td>
                Optional
            </td>
            <td>
                Telephone number and type (work, home, fax)
            </td>
            <td>
                TEL;TYPE=work,VOICE:(111) 555-1212 <br>
                TEL;TYPE=home,VOICE:(404) 386-1017 <br>
                TEL;TYPE=fax:(866) 408-1212 <br>
            </td>
        </tr>
        <tr>
            <td>
                EMAIL
            </td>
            <td>
                Optional
            </td>
            <td>
                Email address
            </td>
            <td>
                EMAIL:smith.j@smithdesigns.com
            </td>
        </tr>
        <tr>
            <td>
                ORG
            </td>
            <td>
                Optional
            </td>
            <td>
                Company name
            </td>
            <td>
                ORG:Smith Designs LLC
            </td>
        </tr>
        <tr>
            <td>
                TITLE
            </td>
            <td>
                Optional
            </td>
            <td>
                Job title
            </td>
            <td>
                TITLE:Lead Designer
            </td>
        </tr>
        <tr>
            <td>
                ADR; TYPE
            </td>
            <td>
                Optional
            </td>
            <td>
                Home or work address in order: Street; City; State; Postal Code; Country
            </td>
            <td>
                ADR;TYPE=WORK,PREF:;;151 Moore Avenue;Grand Rapids;MI;49503;United States of America
            </td>
        </tr>
        <tr>
            <td>
                URL
            </td>
            <td>
                Optional
            </td>
            <td>
                Link to a website
            </td>
            <td>
                URL:https://www.smithdesigns.com
            </td>
        </tr>
        <tr>
            <td>
                VERSION
            </td>
            <td>
                Required
            </td>
            <td>
                The version of the vCard specification.
            </td>
            <td>
                VERSION:3.0
            </td>
        </tr>
        <tr>
            <td>
                END
            </td>
            <td>
                Required
            </td>
            <td>
                All vCards must end with this parameter
            </td>
            <td>
                END:VCARD
            </td>
        </tr>
        </tbody>
    </table>
</div>
