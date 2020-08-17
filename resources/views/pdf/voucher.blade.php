<html>
	<head>
		<title>Fitemos</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style type="text/css">
/* Mobile-specific Styles */

/*# sourceMappingURL=sunny.css.map */

        </style>
	</head>
	<body>
        <h2>Información de la Compañía</h2>
        <div>
            <div style="display:inline-block;width:150px;vertical-align:top;">
                <img src="{{$company['logo']}}"/>
            </div>
            <div style="display:inline-block; margin-left:20px;margin-top:30px;width:400px;">
                <h3>{{$company->name}}</h3>
                <div>
                    {{$company->description}}
                </div>
            </div>
        </div>
        <p>Teléfono: {{$company->phone}} 
            @if($company->mobile_phone)
            / {{$company->mobile_phone}}
            @endif</p>
        <p>Email: {{$company->mail}}</p>
        @if($company->website_url)
            <p>Sitio web: {{$company->website_url}}</p>
        @endif
        @if($company->facebook)
            <p>Facebook: {{$company->facebook}}</p>
        @endif
        @if($company->instagram)
            <p>Instagram: {{$company->instagram}}</p>
        @endif
        @if($company->twitter)
            <p>Twitter: {{$company->twitter}}</p>
        @endif
        @if($company->horario)
            <p>Horario: {{$company->horario}}</p>
        @endif
        <hr />
        <div>
            <div style="display:inline-block;width:250px;vertical-align:top;">
                <img src="{{$image}}"/>
            </div>
            <div style="display:inline-block; margin-left:20px;margin-top:10px;width:300px;">
                <h3>{{$product->name}}</h3>
                <div>
                    {{$product->description}}
                </div>
                <p>Oferta: 
                @if($product->price_type == "offer")
                    <span style="text-decoration: line-through">${{$product->regular_price}}</span><span>${{$product->price}}</span>
                @else
                    {{$product->discount}}
                @endif
                </p>
                @if($product->codigo)
                    <p>Código Ecommerce: {{$product->codigo}}</p>
                @endif
                @if($product->link)
                    <p>Link Ecommerce: {{$product->link}}</p>
                @endif                
                Válido hasta: {{$voucherDate}}

            </div>
        </div>
        <h3>Observaciones</h3>
        <ul>
            <li>Este Voucher solo podrá ser utilizado por usted.</li>
            <li>Este Voucher ya está activo, puede proceder a canjearlo en el comercio.</li>
            <li>Este Voucher podrá volverlo a descargar, si se llega a expirar.</li>
            <li>El Comercio es independiente a Fitemos. Fitemos no se hace responsable sobre los productos o servicios ofrecidos por el comercio.</li>
            <li>Si tiene alguna queja o comentario sobre el comercio puede contactarnos a hola@fitemos.com</li>
            <li>Usted al utilizar este Voucher acepta estar en total conformidad con todo lo antes mencionado.</li>
        </ul>
    </body>
</html>
