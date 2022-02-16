 <!-- Anti-flicker snippet (recommended)  -->
 <style>
     .async-hide {
         opacity: 0 !important
     }

 </style>
 <script>
     (function(a, s, y, n, c, h, i, d, e) {
         s.className += ' ' + y;
         h.start = 1 * new Date;
         h.end = i = function() {
             s.className = s.className.replace(RegExp(' ?' + y), '')
         };
         (a[n] = a[n] || []).hide = h;
         setTimeout(function() {
             i();
             h.end = null
         }, c);
         h.timeout = c;
     })(window, document.documentElement, 'async-hide', 'dataLayer', 4000, {
         'GTM-K9BGS8K': true
     });
 </script>

 <!-- Analytics-Optimize Snippet -->
 <script>
     (function(i, s, o, g, r, a, m) {
         i['GoogleAnalyticsObject'] = r;
         i[r] = i[r] || function() {
             (i[r].q = i[r].q || []).push(arguments)
         }, i[r].l = 1 * new Date();
         a = s.createElement(o),
             m = s.getElementsByTagName(o)[0];
         a.async = 1;
         a.src = g;
         m.parentNode.insertBefore(a, m)
     })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');
     ga('create', 'UA-46172202-22', 'auto', {
         allowLinker: true
     });
     ga('set', 'anonymizeIp', true);
     ga('require', 'GTM-K9BGS8K');
     ga('require', 'displayfeatures');
     ga('require', 'linker');
     ga('linker:autoLink', ["2checkout.com", "avangate.com"]);
 </script>
 <!-- end Analytics-Optimize Snippet -->

 <!-- Google Tag Manager -->
 <script>
     (function(w, d, s, l, i) {
         w[l] = w[l] || [];
         w[l].push({
             'gtm.start': new Date().getTime(),
             event: 'gtm.js'
         });
         var f = d.getElementsByTagName(s)[0],
             j = d.createElement(s),
             dl = l != 'dataLayer' ? '&l=' + l : '';
         j.async = true;
         j.src =
             'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
         f.parentNode.insertBefore(j, f);
     });
 </script>
 <!-- End Google Tag Manager -->
 <meta charset="utf-8" />
 <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/logo.png') }}">
 <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
 <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
 <title>
     @isset($title)
         {{ $title }}
     @else
         SIPPM UNIPA
     @endisset
 </title>

 <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
     name='viewport' />
 <!--     Fonts and icons     -->
 <link rel="stylesheet" type="text/css"
     href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
 <!-- CSS Files -->

 <link href="{{ asset('/css/material-dashboard.css') }}" rel="stylesheet" />

 <style>
     .fw-300 {
         font-weight: 300 !important;
     }

     .fw-400 {
         font-weight: 400 !important;
     }

     .fw-500 {
         font-weight: 500 !important;
     }

 </style>
