<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->

    <style type="text/css">
      body {
          margin: 0;
          font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";
          font-size: 0.75rem;
          font-weight: 300;
          line-height: 1;
          color: #212529;
          text-align: left;
          background-color: #fff;
      }
      .table-bordered td, .table-bordered th {
          border: 1px solid #dee2e6;
      }
      .table td, .table th {
          padding: .5rem;
          vertical-align: top;
          border-top: 1px solid #dee2e6;
      }
      td {
          display: table-cell;
          vertical-align: inherit;
      }
      td p{
        word-break: break-all;
        margin: 0.3em;
      }
      .table {
          table-layout: fixed ;
          width: 100%;
          margin-bottom: 1rem;
          color: #212529;
          border-collapse: collapse;
          border-spacing: 2px;
        }
        p{
          word-wrap: break-word;
          margin: 0.3em;
        }
    </style>

    <title>@yield('title')</title>
  </head>
  <body>


    @yield('body')

  </body>
</html>