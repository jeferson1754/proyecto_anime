<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.8.8/semantic.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.semanticui.min.css">
<link rel="stylesheet" href="//cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">

<style>
    .main-container {
        max-width: 600%;
        margin: 30px 20px;

    }

    table {
        width: 100%;
        background-color: white !important;
        text-align: left;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 5px;

    }

    thead {
        background-color: #5a9b8d !important;
        color: white !important;
        border-bottom: solid 5px #0F362D !important;
    }


    tr:nth-child(even) {
        background-color: #ddd !important;
    }

    tr:hover td {
        background-color: #369681 !important;
        color: white !important;
    }


    div.dataTables_wrapper div.dataTables_filter input {
        margin-right: 10px;
    }

    .flex-container {
        display: flex;
    }

    .max {
        width: 30%;

    }


    @media screen and (max-width: 600px) {
        table {
            width: 100%;
        }

        thead {
            display: none;
        }

        tr:nth-of-type(2n) {
            background-color: inherit !important;
        }

        tr td:first-child {
            background: #f0f0f0 !important;
            font-weight: bold;
            font-size: 1.3em;
        }

        tr:hover td {
            background-color: #369681 !important;
            color: white !important;
        }

        tbody td {
            display: block;
            text-align: center !important;
        }


        tbody td:before {
            content: attr(data-th) !important;
            display: block;
            text-align: center !important;
        }

        .max {
            width: auto;
        }
        /*Letras de "Mostrando tanto de un total XD"*/
        div#example_info {
            font-size: 10px;
        }
    }

        
</style>