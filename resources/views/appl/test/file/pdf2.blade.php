 <html>
    <head>
        <style>
            /** 
                Set the margins of the page to 0, so the footer and the header
                can be of the full height and width !
             **/
            @page {
                margin: 0cm 0cm;
            }

            /** Define now the real margins of every page in the PDF **/
            body {
                margin-top: 2cm;
                margin-left: 2cm;
                margin-right: 2cm;
                margin-bottom: 2cm;
            }


            /** Define the footer rules **/
            footer {
                position: fixed; 
                bottom: 0cm; 
                left: 0cm; 
                right: 0cm;
                height: 2cm;

                /** Extra personal styles **/
                background-color: #eee;
                color: #1f70ab;
                text-align: center;
                line-height: 1.5cm;
            }

            hr{ 
              height: 1px;
              color: red;
              background-color: #eee;
              border: 1px solid silver;
            }
            .response{
            	font-family: Arial, Helvetica, sans-serif;
            	font-size:18px;
            	line-height: 70px;
            }

            .head{padding:20px;background:#f8f8f8;margin-bottom:30px; font-family: Arial, Helvetica, sans-serif;font-size:18px;line-height: 25px;}
            .bord{ border: 1px solid #eee; padding:20px; }

        </style>
    </head>
    <body>
        <main>
            <div class="head">
            <div><span class="" >Name: </span>{{$obj->user->name }}</div>
            <div><span class="" >Test: </span>{{$obj->test->name }}</div>
            </div>
			<div class="">Question</div>
           <div class="response">{!! $obj->response !!}</div>
        </main>
    </body>
</html>
