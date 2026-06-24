<style>
    @font-face {
        font-family: 'OldEnglish';
        src: url({{ storage_path('fonts/oldenglishtextmt.ttf') }}) format("truetype");
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: "Bookman Old Style", "Book Antiqua", Georgia, serif;
        font-size: 10pt;
        color: #000;
        padding: 15px 80px;
    }

    /* ── HEADER (was missing!) ── */
    .header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 4px;
        text-align: center;
    }

    .header-logo {
        width: 70px;
        height: 70px;
        flex-shrink: 0;
        object-fit: contain;
    }

    .header-text {
        text-align: center;
        flex: 1;
    }

    .header-text .region {
        font-size: 10pt;
        font-weight: bold;
    }

    .header-text .division {
        font-size: 10pt;
        font-weight: bold;
        text-transform: uppercase;
    }

    /* ── DIVIDERS ── */
    .divider {
        border-top: 2px solid #000;
        margin: 6px 0;
    }

    .divider-thin {
        border-top: 1px solid #000;
        margin: 6px 0;
    }

    /* ── DOCUMENT TITLE ── */
    .doc-title {
        text-align: center;
        font-size: 13pt;
        font-weight: bold;
        letter-spacing: 2px;
        margin: 10px 0 2px;
        text-transform: uppercase;
    }

    .doc-subtitle {
        text-align: center;
        font-size: 9pt;
        font-style: italic;
        margin-bottom: 10px;
    }

    /* ── BP LINE ── */
    .bp-line {
        font-size: 9.5pt;
        margin-bottom: 6px;
    }

    /* ── INFO TABLES ── */
    .info-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 4px;
        font-size: 9pt;
    }

    .info-table td {
        padding: 2px 4px;
        vertical-align: bottom;
    }

    .info-label {
        font-weight: bold;
        white-space: nowrap;
    }

    .info-underline {
        border-bottom: 1px solid #000;
        min-width: 120px;
        display: inline-block;
        text-align: center;
        padding-bottom: 1px;
        font-weight: bold;
    }

    .info-caption {
        font-size: 7pt;
        text-align: center;
        font-style: italic;
        color: #333;
        margin-top: 1px;
    }

    /* ── CERT TEXT ── */
    .cert-text {
        font-size: 9.5pt;
        line-height: 1.65;
        text-align: justify;
        margin: 8px 0;
    }

    /* ── SERVICE RECORD TABLE ── */
    .sr-outer {
        border: 1.5px solid #000;
        margin: 10px auto;
        width: 100%;
    }

    .sr-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 8pt;
        table-layout: auto;
    }

    .sr-table th {
        border: 1px solid #000;
        padding: 4px 6px;
        text-align: center;
        font-weight: bold;
        font-size: 7.5pt;
        background: #ececec;
        vertical-align: middle;
        line-height: 1.3;
    }

    .sr-table td {
        border: 1px solid #000;
        padding: 3px 6px;
        text-align: center;
        font-size: 8pt;
        vertical-align: middle;
        word-break: break-word;
    }

    .sr-table td.left {
        text-align: left;
    }

    .sr-table tbody tr:nth-child(even) td {
        background: #fafafa;
    }

    .sr-table tfoot td {
        font-weight: bold;
        font-size: 7.5pt;
        background: #f0f0f0;
        text-align: left;
        padding: 3px 6px;
    }

    /* ── COMPLIANCE NOTE ── */
    .compliance {
        font-size: 8.5pt;
        line-height: 1.65;
        text-align: justify;
        margin: 8px 0;
    }

    /* ── SIGNATURE SECTION ── */
    .sig-section {
        margin-top: 16px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }

    .sig-date-box {
        border-top: 1px solid #000;
        margin-top: 30px;
        padding-top: 3px;
        width: 190px;
        text-align: center;
        font-size: 8.5pt;
    }

    .sig-right {
        text-align: center;
        font-size: 9pt;
    }

    .sig-right .certified {
        margin-bottom: 28px;
        font-size: 9pt;
    }

    .sig-name-block {
        border-top: 1px solid #000;
        width: 210px;
        margin: 0 auto;
        padding-top: 3px;
    }

    .sig-name {
        font-weight: bold;
        font-size: 10pt;
        text-transform: uppercase;
    }

    .sig-title {
        font-size: 8.5pt;
    }

    /* ── FOOTER ── */
    @page {
        margin-bottom: 120px;
    }

    .footer-div {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 0 80px 10px 80px;
    }

    .ref-line {
        font-size: 8pt;
        font-style: italic;
        color: #444;
        line-height: 1.5;
    }

    .footer-img {
        width: 90%;
        display: block;
        margin: 0 auto;
        object-fit: contain;
    }

    .page-number::after {
        content: counter(page);
    }

    .page-count::after {
        content: counter(pages);
    }
</style>
