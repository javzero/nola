@extends('store.partials.main')

@section('content')
{{-- @if(Auth::guard('customer')->check())
<div class="CheckoutCart checkout-cart checkout-cart-floating">
    @include('store.partials.checkout-cart')
</div>
@endif --}}
<div class="container padding-bottom-3x mb-1 marg-top-25">
	
	<div class="row product-show">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-5 col-xl-6 col-xs-pull-12 image">
			{{-- Title Mobile --}}
			<div class="title-mobile">
				<span class="text-medium">Categoría:&nbsp;</span>
				<a class="navi-link" href="#">{{ $article->category->name }}</a>
				{{--  Article Name  --}}
				<h2 class="text-normal">{{ $article->name }} {{ $article->color }}</h2>
				<div class="code"><span class="text-medium">Código:</span> #{{ $article->id }}</div>
			</div>
			<div class="row product-gallery">
				<div class="col-xs-12 col-sm-3 col-md-3 pad0">
					<ul class="product-thumbnails">
						@foreach($article->images as $image)
							<li>
								<a href="#{{ $image->id }}">
									<img src="{{ asset('webimages/catalogo/'. $image->name) }}" class="CheckCatalogImg" alt="Producto Nola">
								</a>
							</li>
							@endforeach
						</ul>
					</div>
					<div class="col-xs-12 col-sm-9 col-md-9 images-container pad0">
						<div class="gallery-wrapper">
							@foreach($article->images as $index => $image)
							<div class="gallery-item {{ $index == 0 ? 'active' : '' }}">
							<a href="{{ asset('webimages/catalogo/'. $image->name) }}" data-hash="{{ $image->id }}" data-size="500x750"><i class="icon-zoom-in"></i></a>
						</div>
						@endforeach
					</div>
					<div class="product-carousel owl-carousel">
						@if(!$article->images->isEmpty())
						@foreach($article->images as $image)
						<div data-hash="{{ $image->id }}"><img class="CheckCatalogImg" src="{{ asset('webimages/catalogo/'. $image->name) }}" alt="Product"></div>
						@endforeach
						@else
						<img src="{{ asset($article->featuredImageName()) }}" class="CheckCatalogImg" alt="Producto del Catálogo">
						@endif
					</div>
				</div>
			</div>
		</div>

		<div class="padding-top-2x hidden-md-up"></div>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-7 col-xl-6 products-details">
			{{-- Favs --}}
			<div class="fav-container">
				@if(Auth::guard('customer')->check())
					<a class="AddToFavs fa-icon fav-icon-nofav @if($isFav) fav-icon-isfav @endif"
					data-id="{{ $article->id }}" data-toggle="tooltip" title="Agregar a Favoritos">
					</a>
					@else
					<a href="{{ url('tienda/login') }}" class="fa-icon fav-icon-nofav"></a>
				@endif
			</div>
			{{-- Title Desktop --}}
			<div class="title-desktop">
				<span class="text-medium">Categoría:&nbsp;</span>
				<a class="navi-link" href="#">{{ $article->category->name }}</a>
				{{--  Article Name  --}}
				<h2 class="text-normal">{{ $article->name }} {{ $article->color }}</h2>
				<div class="code"><span class="text-medium">Código:</span> #{{ $article->id }}</div>
			</div>
			
			@if(Auth::guard('customer')->check() && Auth::guard('customer')->user()->group == '2')
				{{-- Article Price and Discount --}}
				@if($article->discount > 0)
				DESCUENTO % {{ $article->discount }}!!
				<span class="h2 d-block">
					<del class="text-muted text-normal">$ {{ $article->price }}</del>
					&nbsp; ${{ calcValuePercentNeg($article->price, $article->discount) }}
				</span>
				@else
				<span class="h2 d-block">$ {{ $article->price }}</span>
				@endif
			@else
				{{-- Reseller Article Price and Discount --}}	
				@if($article->reseller_discount > 0)
					DESCUENTO % {{ $article->reseller_discount }}!!
					<span class="h2 d-block">
						<del class="text-muted text-normal">$ {{ $article->reseller_price }}</del>
						&nbsp; ${{ calcValuePercentNeg($article->reseller_price, $article->reseller_discount) }}
					</span>
				@else
					<span class="h2 d-block">$ {{ $article->reseller_price }}</span>
				@endif
			@endif
			{{-- Id: {{ $article->id }} <br> --}}
			{{-- Article Description --}}
			<div class="row">
				<div class="col-sm-12 description">
					<p>{{ strip_tags($article->description) }}</p>
				</div>
			</div>
			<div class="row">
				{{-- Form --}}
				<div class="col-sm-12 atributes">
					<div class="item">
						<div class="sub-title">Tela: {{ $article->textile }} </div>
					</div>
					

					@if($article->status == 1) @if($article->stock > 0) @if(Auth::guard('customer')->check())
						{{-- Use this to implement AJAX AddToCart --}}
						{{-- {!! Form::open(['class' => 'AddToCart form-group item']) !!} --}}
						{!! Form::open(['route' => 'store.addtocart', 'method' => 'POST', 'class' => 'price']) !!}	
							{{ csrf_field() }}
								<input type="hidden" name="article_id" value="{{ $article->id }}">
								<div class="sub-title">Talles </div>
								<div class="item btn-group-toggle atribute-selector" data-toggle="buttons">
									{{-- Sizes --}}
									@foreach($article->atribute1 as $size)
										<label class="SizeSelector btn btn-main-sm-hollow">
											<input name="size_id" value="{{ $size->id }}" type="radio" autocomplete="off"> {{ $size->name }}
										</label>
									@endforeach
								</div>
								{{-- Display Remaining Stock --}}
								<div class="row available-stock">
									<span class="AvailableStock action-info-container">{{-- Data from backend --}}</span>
								</div>
								@if($article->status == 1)
								<div class="input-with-btn">
									<input id="MaxQuantity" class="form-control input-field short-input" name="quantity" type="number" min="1" max="{{ $article->stock }}" value="1" placeholder="1" required>
									<input type="submit" id="AddToCartFormBtn" class="btn input-btn" value="Agregar al carro" disabled>
								</div>
								@else
									Artículo no disponible al momento
								@endif
								<input type="hidden" value="{{ $article->id }}" name="articleId">
							{!! Form::close() !!}
							<br>
							{{-- {!! Form::open(['class' => 'AddToCart form-group price']) !!}
								{{ csrf_field() }}	
								<div class="input-with-btn">
									<input class="form-control input-field short-input" name="quantity" type="number" min="1" max="{{ $article->stock }}" value="1" placeholder="1" required>
									<button class="btn input-btn">Agregar al carro</button>
								</div>
								<input type="hidden" value="{{ $article->id }}" name="articleId">
							{!! Form::close() !!} --}}
							@else
								<a href="{{ url('tienda/login') }}" class="btn input-btn">Comprar</a>
							@endif
						@else
							No hay stock disponible
						@endif
					@else
						Este artículo no está disponible al momento
					@endif
							
				</div>
			</div>
			<a class="back-btn" href="javascript:history.go(-1)"><i class="icon-arrow-left"></i>&nbsp;Volver a la tienda</a>
		</div> {{-- Product Details --}}
	</div>
</div>
	
<!-- Photoswipe container // This Shows Big Image Preview -->
<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="pswp__bg"></div>
	<div class="pswp__scroll-wrap">
		<div class="pswp__container">
			<div class="pswp__item"></div>
			<div class="pswp__item"></div>
			<div class="pswp__item"></div>
		</div>
		<div class="pswp__ui pswp__ui--hidden">
			<div class="pswp__top-bar">
				<div class="pswp__counter"></div>
				<button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
				<button class="pswp__button pswp__button--share" title="Share"></button>
				<button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
				<button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
				<div class="pswp__preloader">
					<div class="pswp__preloader__icn">
						<div class="pswp__preloader__cut">
							<div class="pswp__preloader__donut"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
				<div class="pswp__share-tooltip"></div>
			</div>
			<button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
			<button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
			<div class="pswp__caption">
				<div class="pswp__caption__center"></div>
			</div>
		</div>
	</div>
</div>
<div id="Error"></div>
@endsection

@section('scripts')
	@include('store.components.bladejs')
	<script>
		$(document).ready(function(){
			//  Check Stock
			$('.SizeSelector').on('click', function(){
				let size = $(this).children('input').val();
				let route = "{{ url('tienda/checkSizeStock') }}";
				let articleId = "{{ $article->id }}";
				
				checkSizeStock(route, articleId, size);
			});

			// $('.input-with-btn').on('click', function(){
			// 	if($('#AddToCartFormBtn').prop('disabled', true)){
			// 		$('.AvailableStock').html("Seleccioná un talle");
			// 	}
			// });
		});
	</script>
@endsection
