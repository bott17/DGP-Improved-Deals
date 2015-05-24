function Sparkv2 (indicadorEscuchando){
	
	console.log("ensemble...");
	//this.audioComponent = new AudioComponent();
	this.indicadorEscuchando = indicadorEscuchando;
	document.getElementById(this.indicadorEscuchando).src = 'images/imgspark/mic.gif';
	
}

Sparkv2.prototype.cambiarImgagenRecurso = function(srcNuevaImg){
	document.getElementById(this.indicadorEscuchando).src = srcNuevaImg;
};


Sparkv2.prototype.hablar = function(text, callback){
	// say a message
    var u = new SpeechSynthesisUtterance();
    u.text = text;
    u.lang = 'es-Es';
 
    u.onend = function () {
        if (callback) {
            callback();
        }
    };
 
    u.onerror = function (e) {
        if (callback) {
            callback(e);
        }
    };
 
    speechSynthesis.speak(u);
};


// ask a question and get an answer
Sparkv2.prototype.pregunta = function (text, callback) {
	
	var tempSpark = this;
    // ask question
    this.hablar(text, function () {
        // get answer
        var recognition = new webkitSpeechRecognition();
        recognition.continuous = false;
        recognition.interimResults = false;
        recognition.lang = 'es';
        
        
        recognition.onerror = function(event) {
		    if (event.error == 'no-speech') {
		       tempSpark.cambiarImgagenRecurso('images/imgspark/mic.gif');
		      ignore_onend = true;
		    }
		    if (event.error == 'audio-capture') {
		       tempSpark.cambiarImgagenRecurso('images/imgspark/mic.gif');
		      ignore_onend = true;
		    }
		    if (event.error == 'not-allowed') {
		      if (event.timeStamp - start_timestamp < 100) {
		        //showInfo('info_blocked');
		      } else {
		        //showInfo('info_denied');
		      }
		      ignore_onend = true;
		    }
		};
  
  
        recognition.onstart = function() {
		    //console.log("start");
		    tempSpark.cambiarImgagenRecurso('images/imgspark/mic-animate.gif');
		};
		 
        recognition.onend = function (e) {
            if (callback) {
                callback('no results');
                
                 tempSpark.cambiarImgagenRecurso('images/imgspark/mic.gif');
            }
        };
 
        recognition.onresult = function (e) {
            // cancel onend handler
            recognition.onend = null;
            if (callback) {
            	//console.log("Resultado " + final_transcript + " // " + e.results[0][0].transcript);
                callback(null, {
                    final_transcript: e.results[0][0].transcript,
                    confidence: e.results[0][0].confidence
                });
                tempSpark.cambiarImgagenRecurso('images/imgspark/mic.gif');
            }
        };
 
        // start listening
        recognition.start();
    });
};

function capitalize(s) {
	var first_char = /\S/;
  return s.replace(first_char, function(m) { return m.toUpperCase(); });
}
