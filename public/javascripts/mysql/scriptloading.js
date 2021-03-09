
var loader = {animate : true};
function animationLoading(){
  async function getDeg(prop){
    var value = "",inc = false;
    for(var c of prop){
      if(c=="d"){inc=false}
      if(inc==true){value+=c;}
      if(c == "("){inc=true;}
    }
    return parseInt(value);
  }
  async function getBright(prop){
    var value = "",inc = false;
    for(var c of prop){
      if(c==")"){inc=false}
      if(inc==true){value+=c;}
      if(c == "("){inc=true;}
    }
    return parseFloat(value);
  }
  async function animBig(){
    var elem = document.getElementById('loading').children[0]
    if(elem.style.transform == ""){
      elem.setAttribute('style','transform:rotate(180deg)')
    }else{
      var newDeg = await getDeg(elem.style.transform)+180;
      elem.setAttribute('style','transform:rotate('+newDeg+'deg)')
    }
  }
  async function animSmall(){
    var elem = document.getElementById('loading').children[1]
    if(elem.style.transform == ""){
      elem.setAttribute('style','transform:rotate(202.5deg)')
    }else{
      var newDeg = await getDeg(elem.style.transform)+202.5;
      elem.setAttribute('style','transform:rotate('+newDeg+'deg)')
    }
  }
  animBig();
  animSmall();
  setTimeout(async function(){
    if(loader.animate == true){animationLoading()}
  },1000);
}
