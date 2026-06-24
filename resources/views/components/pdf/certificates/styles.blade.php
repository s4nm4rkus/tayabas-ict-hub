<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    @font-face {
        font-family: 'OldEnglish';
        src: url({{ storage_path('fonts/oldenglishtextmt.ttf') }}) format("truetype");
    }

    body {
        font-family: "Bookman Old Style", serif;
        font-size: 11pt;
        color: #000;
        padding: 15px 80px;
    }

    /* Header Styles */
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

    .divider {
        border-top: 2px solid #000;
        margin: 8px 0;
    }

    /* Content Styles */
    .cert-title {
        text-align: center;
        font-size: 13pt;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin: 20px 0;
    }

    .salutation {
        font-size: 11pt;
        font-weight: bold;
        margin-bottom: 16px;
    }

    .body-text {
        font-size: 11pt;
        line-height: 1.8;
        text-align: justify;
        margin-bottom: 14px;
    }

    .comp-table {
        width: 70%;
        border-collapse: collapse;
        margin: 16px 0 0 100px;
        font-size: 11pt;
    }

    .comp-table td {
        padding: 5px 12px;
    }

    .comp-table td:first-child {
        width: 70%;
        font-weight: bold;
    }

    .comp-table td:last-child {
        text-align: right;
        width: 30%;
    }

    .comp-table tr.total td {
        font-weight: bold;
        border-top: 2px solid #000;
    }

    .issue-line {
        margin: 20px 0;
        font-size: 11pt;
        line-height: 1.8;
        text-align: justify;
    }

    .signature-block {
        margin-top: 40px;
    }

    .signature-name {
        font-weight: bold;
        font-size: 12pt;
        text-transform: uppercase;
    }

    .signature-title {
        font-size: 10pt;
    }

    /* Footer Styles */
    .footer-div {
        position: absolute;
        bottom: 10px;
        left: 0;
        right: 0;
        padding: 0 80px;
    }

    .footer-img {
        width: 90%;
        display: block;
        margin: 0 auto;
    }

    .ref-line {
        font-size: 9pt;
        font-family: "Bookman Old Style", serif;
        font-style: italic;
        color: #555;
        margin-top: 6px;
        text-align: left;
    }

    .highlight {
        font-weight: bold;
        text-transform: uppercase;
    }
</style>
