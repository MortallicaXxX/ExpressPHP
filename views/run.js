thorium.onReady = async function(self){

  self.conf = {
   id : 'app', // id de la div contenant l'app , par défaut si non spécifier 'app-thorium'
   app : document.getElementById('app'),
   parent : document.body, // le parent de l'app thorium , par défault document.body
   stats : true, // définis si l'utilitaire CPU/FPS MEMORY doit être présent ou non ( défaut true )
   filters : true, // définis si l'utilitaire des filtres doit être présent ou non ( défaut true )
  }

  await (new THORUS).parse(self.app);
  console.log(self.initialise());

}
