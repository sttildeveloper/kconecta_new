@php
    $legalCheckboxPrefix = $legalCheckboxPrefix ?? 'registration';
    $termsVersion = (string) config('compliance.legal_acceptance.documents.terms', '1.0');
    $privacyVersion = (string) config('compliance.legal_acceptance.documents.privacy', '1.0');
@endphp

<fieldset class="legal-acceptance" aria-describedby="{{ $legalCheckboxPrefix }}-legal-note">
    <legend>Documentos legales obligatorios</legend>
    <input type="hidden" name="legal_terms_version" value="{{ $termsVersion }}">
    <input type="hidden" name="legal_privacy_version" value="{{ $privacyVersion }}">

    <label class="legal-acceptance__option" for="{{ $legalCheckboxPrefix }}-terms-accepted">
        <input
            id="{{ $legalCheckboxPrefix }}-terms-accepted"
            name="terms_accepted"
            type="checkbox"
            value="1"
            required
            {{ old('terms_accepted') ? 'checked' : '' }}
        >
        <span>
            Acepto los
            <a href="{{ route('legal.terms') }}" target="_blank" rel="noopener noreferrer">T&eacute;rminos y condiciones</a>
            (versi&oacute;n {{ $termsVersion }}).
        </span>
    </label>

    <label class="legal-acceptance__option" for="{{ $legalCheckboxPrefix }}-privacy-acknowledged">
        <input
            id="{{ $legalCheckboxPrefix }}-privacy-acknowledged"
            name="privacy_acknowledged"
            type="checkbox"
            value="1"
            required
            {{ old('privacy_acknowledged') ? 'checked' : '' }}
        >
        <span>
            He le&iacute;do la
            <a href="{{ route('legal.privacy') }}" target="_blank" rel="noopener noreferrer">Pol&iacute;tica de privacidad</a>
            (versi&oacute;n {{ $privacyVersion }}).
        </span>
    </label>

    <small id="{{ $legalCheckboxPrefix }}-legal-note">
        El registro est&aacute; reservado a personas de {{ (int) config('legal.minimum_age', 18) }} a&ntilde;os o m&aacute;s.
    </small>
</fieldset>
