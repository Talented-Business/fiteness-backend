<html>
	<head>
		<title>Fitemos</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style type="text/css">
/* Mobile-specific Styles */

/*# sourceMappingURL=sunny.css.map */
            p {
                margin:0px;
            }
            .product-description {
                width: 500px;
            }
        </style>
	</head>
	<body>
        <h2>Información de la Compañía</h2>
        <div>
            <div style="display:inline-block;width:150px;vertical-align:top;">
                <img src="{{$company['logo']}}"/>
            </div>
            <div style="display:inline-block; margin-left:20px;width:400px;">
                <h3>{{$company->name}}</h3>
                <div class="product-description">
                    <p>{{$company->description}}</P>
                </div>
            </div>
        </div>
        <p style="margin-top:20px">Teléfono: {{$company->phone}} 
            @if($company->mobile_phone)
            / {{$company->mobile_phone}}
            @endif</p>
        <!-- <p>Email: {{$company->mail}}</p> -->
        <p><span>Email:</span> <a href="mailto:{{$company->mail}}"><b>{{$company->mail}}</a></p>
        @if($company->website_url)
            <p><span>Sitio web:</span> <a target="_blank" href={{$company->website_url}}><b>{{$company->website_url}}</b></a></p>
        @endif
        @if($company->facebook)
            <p><span>Facebook:</span> <a target="_blank" href={{$company->facebook}} ><b>{{$company->facebook}}</b></a></p>
        @endif
        @if($company->instagram)
            <p><span>Instagram:</span> <a target="_blank" href={{$company->instagram}} ><b>{{$company->instagram}}<b></a></p>
        @endif
        @if($company->twitter)
            <p><span>Twitter:</span> <a target="_blank" href={{$company->twitter}} ><b>{{$company->twitter}}<b></a></p>
        @endif
        @if($company->horario)
            <span>Horario:</span> {{$company->horario}}
        @endif
        @if($company->address)
            <p style="margin-bottom:20px">dirección: {{$company->address}}</p>
        @endif
        <hr />
        <div style="margin-top:30px">
            <div style="display:inline-block;width:250px;vertical-align:top;">
                <img src="{{$image}}"/>
            </div>
            <div style="display:inline-block; margin-left:20px;width:400px; margin-top:15px">
                <h3>{{$product->name}}</h3>
                <div>
                    {{$product->description}}
                </div>
                <br>
                <br>
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
                    <p><span>Link Ecommerce:</span> <a target="_blank" href={{$product->link}} ><b>{{$product->link}}<b></a></p>
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
