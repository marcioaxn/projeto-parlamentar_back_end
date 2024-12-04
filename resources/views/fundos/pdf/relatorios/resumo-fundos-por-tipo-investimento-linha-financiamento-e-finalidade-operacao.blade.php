<html>

<head>
    <style>
        /**
                Set the margins of the page to 0, so the footer and the header
                can be of the full height and width !
             **/
        /** Define the margins of your page **/
        @page {
            margin: 91px 25px;
            counter-increment: page;
            counter-reset: page 1;
        }

        header {
            position: fixed;
            top: -69px;
            left: 0px;
            right: 0px;
            height: 65px;

            /** Extra personal styles **/
            background-color: #03a9f4;
            color: white;
            text-align: center;
            line-height: 33px;
        }

        footer {
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;
            height: 21px;

            border-top: 1px solid rgb(165, 164, 164);

            /** Extra personal styles **/
            background-color: #FFFFFF;
            color: #000000;
            text-align: center;
            line-height: 17px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            font-size: 11px;
        }

        footer:after {
            content: counter(page);
        }

        #nomeRelatorio {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            font-size: 13px;
            padding-top: 4px;
            padding-bottom: 4px;
            font-weight: bold;
        }

        #filtros {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            font-size: 12px;
            padding-top: 4px;
            padding-bottom: 4px;
        }

        th {
            border-top: 1px solid rgb(165, 164, 164);
            border-bottom: 1px solid rgb(165, 164, 164);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            font-size: 11px;
        }

        td {
            border-bottom: 1px solid rgb(165, 164, 164);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            font-size: 11px;
        }

        p {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            font-size: 11px;
        }
    </style>
</head>

<body>
    <!-- Define header and footer blocks before your content -->
    <header>
        <img src="{!! converterImagemParaBase64('http://10.216.4.71/visao360/public/img/cabecalho_landscape_fundos.jpg') !!}" width="100%" height="100%" />
    </header>

    <footer style="text-align: right; padding-right: 4px;">
        <span style="text-align:left; font-size: 10px; color: #696969;">Relatório gerado em <?php echo date('d/m/Y'); ?> às
            <?php echo date('H:i:s'); ?></span> {!! '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;página ' !!}
    </footer>

    <main>
        @include('fundos.pdf.relatorios.tabela')
    </main>
</body>

</html>
