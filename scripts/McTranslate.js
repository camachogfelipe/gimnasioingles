
/*

author:
  - [MannyCompany](Jose Manuel Vargas Navarro)

licenses:
  - [MIT License](http://mannycompay.com/mctranslate/license.txt)

version:
  - [0.7] 

sitio:
  - [demo](http://mannycompay.com/mctranslate/#Demo)
  - [download](http://mannycompay.com/mctranslate/#Download)

*/

var Detect = new Class({
	Implements: [Events, Request.JSONP, Options],
	options: {
		pregunta: 'MannyCompany tambien habla tu idioma. ¿Quieres cambiarlo?',
		etiquetas: ['p']
	},
	initialize: function(options){
		this.setOptions(options);
		this.addEvent('onDetectIdioma',function(){
			if(this.nativoL != this.nuevoL)
				this.barra();
		})
		this.nuevo();
		if(this.options.nativo == undefined)
			this.nativo();
		else{
			this.nativoL = this.options.nativo;
			this.fireEvent('onDetectIdioma');
		}
	},
	div: new Element('div',{
		'styles':{
			'background-color': "#000",
			'padding': '10px',
			'text-align':'center',
			'color':'#fff'
		}
	}),
	nativo: function(){
		var text = this.options.pregunta;
		new Request.JSONP({
			url: 'https://ajax.googleapis.com/ajax/services/language/detect',
			data:{
				'v':'1.0',
				'q':text
			},
			onSuccess: function(r){
				this.nativoL = r.responseData.language;
				this.fireEvent('onDetectIdioma');
			}.bind(this)
		}).send();
	},
	nuevo: function(){
		this.nuevoL = (navigator.browserLanguage == undefined)?(navigator.language == undefined)?(navigator.userLanguage == undefined)?(navigator.systemLanguage == undefined)?null:navigator.systemLanguage:navigator.userLanguage:navigator.language:navigator.browserLanguage;
		this.nuevoL = this.nuevoL.split('-')[0];
	},
	barra: function(nuevo){
		new Request.JSONP({
			url: 'https://ajax.googleapis.com/ajax/services/language/translate',
			data:{
				'v':'1.0',
				'q':this.options.pregunta,
				'langpair': this.nativoL+'|'+this.nuevoL
			},
			onSuccess: function(r){
				this.div.set('text',r.responseData.translatedText).inject(document.body,'top');
				new Element('img',{
					'src':'/ok.jpg',
					'styles':{
						'margin-left':'10px',
						'cursor':'pointer'
					},
					'events':{
						'click':function(){
							new McTranslate({
								'nativo': this.nativoL,
								'nuevo': this.nuevoL,
								'etiquetas': this.options.etiquetas
							});
							this.div.destroy();
						}.bind(this)
					}
				}).inject(this.div);
				new Element('img',{
					'src':'/no.jpg',
					'styles':{
						'margin-left':'10px',
						'cursor':'pointer'
					},
					'events':{
						'click':function(){
							this.div.destroy();
						}.bind(this)
					}
				}).inject(this.div);
			}.bind(this)
		}).send();
	}
});

var McTranslate = new Class({
	Implements: [Elements, Options, Request.JSONP, Events],
	options: {
		etiquetas:['p'],
		nativo:null,
		nuevo:null
	},
	initialize: function(options){
		this.setOptions(options);
		if(!Browser.ie)this.options.etiquetas.include('title')
		if(this.options.nativo==null)alert('No se puede traducir');
		Array.each(this.options.etiquetas,function(e){
			$$(e).each(function(f){
				this.traduce(f,this.options.nuevo,this.options.nativo);
			},this);
		},this);
	},
	traduce: function(e,nuevo,nativo){
		var respaldo=e.get('respaldo');
		if(respaldo==null) {
			e.setProperty('respaldo',e.get('html'));
		}
		var text=e.get('respaldo');
		new Request.JSONP({
			url:'https://ajax.googleapis.com/ajax/services/language/translate',
			data:{
				'v':'1.0',
				'q':text,
				'langpair':nativo+'|'+nuevo
			},
			onSuccess:function(r){
				e.set('html',r.responseData.translatedText);
			}
		}).send()
	}
});

Array.implement({Detect: function(){new Detect({etiquetas: this})}});