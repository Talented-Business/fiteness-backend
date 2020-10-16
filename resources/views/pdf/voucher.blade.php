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
        <div style="width:150px;vertical-align:top;opacity:0.5">
            <img src="{{asset('media/logos/Fitmose-logo.png')}}" alt="Fitemos" width="150"/>
        </div>
        <div>
            <h2>Miembro</h2>
            <div style="margin:0 9px;padding:0 9px;">
                <div style="display:inline-block;width:50%">
                    <span>Nombre:</span>
                    <span>{{$customer->first_name}} {{$customer->last_name}}</span>
                </div>
                <div style="display:inline-block;width:50%">
                    <span>Teléfono:</span>
                    <span>{{$customer->whatsapp_phone_number}}</span>
                </div>
            </div>
            <div style="margin:0 9px;padding:0 9px;">
                <div style="display:inline-block;width:50%">
                    <span>Correo:</span>
                    <span>{{$customer->email}}</span>
                </div>
                <div style="display:inline-block;width:50%">
                    <span>Membresía: </span>
                    <span style="color:green">Activa</span>
                </div>
            </div>
        </div>
        <hr />
        <div style="margin-top:-10px">
            <h2>Comercio</h2>
            <div>
                <div style="display:inline-block;width:150px;vertical-align:top;">
                    <img src="{{$company['logo']}}" width="150"/>
                </div>
                <div style="display:inline-block; margin-left:20px;width:400px;">
                    <h3>{{$company->name}}</h3>
                    <div class="product-description">
                        <p>{{$company->description}}</P>
                    </div>
                </div>
            </div>
            <div style="margin-top:20px">&nbsp;</div>
            <div style="margin:0 9px;padding:0 9px;">
                <div style="display:inline-block;width:50%">
                    <span>Teléfono:</span> 
                    {{$company->phone}} 
                    @if($company->mobile_phone)
                    / {{$company->mobile_phone}}
                    @endif
                </div>
                <!-- <p>Email: {{$company->mail}}</p> -->
                <div style="display:inline-block;width:50%">
                    <span>Website:</span> 
                    @if($company->website_url)
                        {{$company->website_url}}
                    @endif
                </div>
            </div>    
            <div style="margin:0 9px;padding:0 9px;">
                <div style="display:inline-block;width:50%">
                    <span>Correo:</span> {{$company->mail}}
                </div>    
            
                <div style="display:inline-block;width:50%">
                    <span>Facebook:</span> 
                    @if($company->facebook)
                        {{$company->facebook}}
                    @endif    
                </div>
            </div>    
            <div style="margin:0 9px;padding:0 9px;">
                <div style="display:inline-block;width:50%">
                    <span>Horario:</span>             
                    @if($company->horario)
                        {{$company->horario}}
                    @endif    
                </div>
                <div style="display:inline-block;width:50%">
                    <span>Instagram:</span>
                    @if($company->instagram)
                     {{$company->instagram}}
                    @endif
                </div>    
            </div>
            <div style="margin:0 9px;padding:0 9px;">    
                <div style="display:inline-block;width:50%">
                    <span>Dirección:</span> 
                    @if($company->address)
                        {{$company->address}}
                    @endif
                </div>        
                <div style="display:inline-block;width:50%">
                    <span>Twitter:</span> 
                    @if($company->twitter)
                        {{$company->twitter}}
                    @endif    
                </div>    
            </div>
            <div style="margin-bottom:0px">&nbsp;</div>
        </div>
        <hr />
        <div style="margin-top:-10px">
            <h2>Producto</h2>
            <div>
                <div style="display:inline-block;width:150px;vertical-align:top;">
                    <img src="{{$image}}" width="150"/>
                </div>
                <div style="display:inline-block; margin-left:20px;width:400px;vertical-align:top;">
                    <h3 style="vertical-align:top;">{{$product->name}}</h3>
                    <div class="product-description">
                        {{$product->description}}
                    </div>
                </div>
            </div>
            <br>
            <div style="margin:0 9px;padding:0 9px;">    
                <div style="display:inline-block;width:50%">
                    Oferta: 
                    @if($product->price_type == "offer")
                        <span style="text-decoration: line-through">${{$product->regular_price}}</span><span>${{$product->price}}</span>
                    @else
                        {{$product->discount}}%
                    @endif
                </div>
            
                <div style="display:inline-block;width:50%">
                    Código E-commerce: 
                    @if($product->codigo)
                        {{$product->codigo}}
                    @endif
                </div>
            </div>
            <div style="margin:0 9px;padding:0 9px;">    
                <div style="display:inline-block;width:50%">     
                    Válido: {{$voucherDate}}
                </div>
                <div style="display:inline-block;width:50%">
                    <span>E-commerce Link:</span> 
                    @if($product->link)
                        {{$product->link}}
                    @endif          
                </div>
            </div>
        </div>
        <h3>Observaciones</h3>
        <ul>
            <li>Este Voucher solo podrá ser utilizado por usted.</li>
            <li>Este Voucher ya está activo, puede proceder a canjearlo en el comercio.</li>
            <li>Este Voucher podrá volverlo a descargar, si se llega a expirar.</li>
            <li>El Comercio es independiente a Fitemos. Fitemos no se hace responsable sobre los productos o servicios ofrecidos por el comercio.</li>
            <li>Si tiene alguna queja o comentario sobre el comercio puede contactarnos a hola@fitemos.com.</li>
            <li>Usted al utilizar este Voucher acepta estar en total conformidad con todo lo antes mencionado.</li>
        </ul>
    </body>
</html>
