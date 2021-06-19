<?php
  class GAMEBOARD extends thcomponents{
    function __construct(){
      parent::__construct(array(
        "type" => "gameboard",
        "prop" => array(
          "id" => "gameboard"
        ),
        "childrens" => array(
          array(
            "type" => "container",
            "prop" => array(
              "id" => "gameboard-container"
            ),
            "proto" => array(
                "grille" => null,
                "f" => "(function(self){return new Promise(async function(next){

                  function randomIntFromInterval(min, max) { // min and max included
                    return Math.floor(Math.random() * (max - min + 1) + min);
                  }

                  var grille = {
                    type:'div',
                    prop:{id:'grille'},
                    childrens:[]
                  }

                  var height = thorium.screen.height , width = thorium.screen.width;

                  for(let iL = 0 ; iL <= Math.floor(width/43) ; iL++){
                    var ligne = {
                      type:'div',
                      prop:{id:iL,class:'ligne'},
                      childrens : []
                    }
                    for(let iC = 0 ; iC <= Math.floor(height/43) ; iC++){
                      ligne.childrens.push({
                        type:'div',
                        prop:{id:iC,class:'cell'},
                        childrens : [],
                        proto : {
                          onClick : function(){
                            this.turnActive();
                          }
                        }
                      })
                    }
                    grille.childrens.push(ligne);
                  }

                  grille.childrens[randomIntFromInterval(0,Math.floor(width/43))].childrens[randomIntFromInterval(0,Math.floor(height/43))].childrens.push({
                    type:'div',
                    prop:{id:'player'},
                    proto : {
                      player : null,
                      surMouvement : null,
                      hover : false,
                      passage : 0,
                      onInitialise : function(){
                        var self = this;
                        self.player.set(self.e);
                        self.surMouvement.set(thorium.controls.addEventListener('mousemove',function(self){

                          var mouse = thorium.controls.mouse , position = self.position.get();
                          var distanceX = Math.floor((Math.abs(mouse.x - (position.x + (position.width/2))))) ,
                          distanceY = Math.floor((Math.abs(mouse.y - (position.y + (position.height/2)))));

                          var nbrBlockx = Math.floor(distanceX/43),
                          nbrBlocky = Math.floor(distanceY/43)

                          var distanceBlock = nbrBlockx + nbrBlocky;

                          if(distanceBlock <= 2)self.mouvement();

                        },self))

                      },
                      mouvement : function(){

                        var self = this;

                        /*
                          *{desc} fonction de recherche des cellules adjacentes disponibles.
                          *{paramètres} prend l'indice de colone ainsi que l'indice ligne. L'options permet de choisir si l'on veut les cellules disponibles ou
                                        celle disponibles représentant la meilleure distance d'écart par rapport à la souris
                            *{param1} il = indice ligne
                            *{param2} ic = indice colone
                            *{param3} keepBestDistance = option 'garder ses distnces' par défault = true
                          *{return} array<object>
                        */
                        function findBetterMinPath(il,ic , keepBestDistance = true){
                          return new Promise(async function(next){

                            var result = [];
                            result.__proto__.keepBestDistance  = function(){
                              try{
                                if(this[0].distance < this[1].distance)this.shift();
                                if(this[1].distance < this[0].distance)this.pop();
                              }catch(err){
                              }

                            }

                            for(const i of [0,1,2,3]){
                              try{

                                var c = ic , l = il;

                                if(i == 0)c--;
                                if(i == 1)c++;
                                if(i == 2)l--;
                                if(i == 3)l++;

                                var e = self.e.parentNode.parentNode.parentNode.children[l].children[c];
                                if(e.active.get() == true)throw {err:2}
                                if(typeof e == 'undefined')throw {err:1}

                                var ePosition = e.position.get();
                                ePosition = thorium.vec2(ePosition.x,ePosition.y);
                                var cursorPosition = thorium.vec2(thorium.controls.mouse.x,thorium.controls.mouse.y);
                                result.push({position:ePosition,distance:ePosition.distance(cursorPosition),il:l,ic:c,e:e});
                                if(keepBestDistance == true)result.keepBestDistance();
                                if(i == 3)next(result);
                              }catch(e){
                                // console.log(e);
                                if(i == 3)next(result);
                              }
                            }

                          })
                        }

                        async function findBetterLongPath(il,ic,generation = 5){

                          const result = {
                            bestMatchNode : {
                              distance : 0,
                              node : null,
                              set : function(node){
                                this.node = node;
                                this.distance = node.distance;
                              },
                              reverse : async function(result = [] , node = this.node){
                                result.push(node);
                                if(node.parentNode)return await this.reverse(result , node.parentNode);
                                else return result.reverse();
                              }
                            },
                            nodes : await findBetterMinPath(il,ic,false),
                            push : function(value){this.nodes.push(value);},
                            trim : async function(){

                              const self = this;
                              const indexedTrim = {
                                nodes : self.nodes,
                                bestMatchNode : self.bestMatchNode,
                                analyse : async function(nodes){
                                  for await(const i of Array.from({length : nodes.length} , (x,i) => i)){
                                    // console.log(nodes);
                                    // if(nodes[i].distance > indexedTrim.maxDistance)indexedTrim.maxDistance = nodes[i].distance;
                                    nodes[i].PowIdex = nodes[i].distance/indexedTrim.bestMatchNode.distance
                                    if(nodes[i].nodes) await this.analyse(nodes[i].nodes);
                                  }
                                }
                              }

                              return new Promise(async function(next){
                                await indexedTrim.analyse(indexedTrim.nodes)
                                .then(function(){
                                  next(indexedTrim);
                                })
                              })

                            },
                            genById : {},
                            genNodesById : async function(node,genId){ // génération des nodes du node et répertorie le nombre de node de la génération
                              let l = node.il , c = node.ic;
                              node.nodes = await findBetterMinPath(l,c,false); // ajout des nodes manquants au node étudier
                              if(!this.genById[String(genId)]) this.genById[String(genId)] = 0;
                              this.genById[String(genId)] += node.nodes.length;
                            },
                            analyse : async function(nodes,genId,parent = null){ // lancement de l'analyse des nodes;
                              // parcour des nodes afin d'ajouter leurs propres nodes si la génération n'est pas finie
                              for(const i of Array.from({length : nodes.length} , (x,i) => i )){
                                if(nodes[i].distance > this.bestMatchNode.distance)this.bestMatchNode.set(nodes[i]);

                                if(!parent){ // ajout du nodeRoot et passage de la référence à chaque enfants
                                  nodes[i].rootNode = nodes[i];
                                  nodes[i].root = result;
                                }else{
                                  nodes[i].parentNode = parent;
                                  nodes[i].rootNode = parent.rootNode;
                                }

                                // nodesResults = result;

                                if(genId < generation){
                                  await this.genNodesById(nodes[i],genId);
                                  await this.analyse(nodes[i].nodes,genId + 1,nodes[i]);
                                }else return null;
                              }
                            }

                          }

                          result.genById[String(0)] = result.nodes.length;

                          return new Promise(async function(next){
                            await result.analyse(result.nodes, 1)
                            .then(async function(){
                              next(await result.trim());
                            })
                          })

                        }

                        var il = self.e.parentNode.parentNode.getAttribute('id');
                        var ic = self.e.parentNode.getAttribute('id');
                        var mousePosition = thorium.controls.mouse;
                        var elementPosition = self.e.position.get();
                        var centre = elementPosition.centre;

                        var x = Math.abs(centre.x - mousePosition.x);
                        var y = Math.abs(centre.y - mousePosition.y);

                        if(mousePosition.x <= centre.x) x = -x;
                        if(mousePosition.y <= centre.y) y = -y;

                        if(!this.sessionMouvement.get()){
                          findBetterLongPath(il,ic)
                          .then(async function(result){
                            self.sessionMouvement.set(await result.bestMatchNode.reverse());
                            // console.log(result);
                            // if(result[0])result[0].e.appendChild(self.e);
                            // self.e.updatePosition();
                          })
                        }

                      },
                      sessionMouvement : null,
                      onMouseEnter : function(){
                        this.hover.set(true);
                        var passages = this.passage.get();
                        this.passage.set(passages + 1);
                        console.log('Vous êtes passer dessus '+passages+' fois !!');
                      },
                      onMouseLeave : function(){
                        this.hover.set(false);
                        console.log('Elle vous échape !!');
                      },
                      onFrameUpdate : function(){
                        const self = this;
                        const x = this.sessionMouvement.get();
                        if(x){
                          console.log(x);
                          x[0].e.appendChild(self.e);
                          x.shift();
                          self.e.updatePosition();
                          if(x.length == 0)this.sessionMouvement.set(null);
                          else this.sessionMouvement.set(x);
                        }
                      }
                    }
                  })

                  next(grille)
                })})",
                "onInitialise" => "function(self){
                  console.log(self);
                  (eval(self.f.get())(self))
                  .then(function(result){
                    new UI([result]).buildIn(self.e)
                    .then(function(){
                      self.grille.set(document.getElementById('grille'));
                      self.grille.get().initialise();
                    })
                  })
                }"
            )
          )
        )
      ));
    }

    function __destruct(){}
  }

  // var_dump(new GAMEBOARD());
  $thorium -> addCss($docHTML,'gameboard-style',"
    gameboard{
      display : grid;
      grid-template-columns : 1fr;
      grid-template-rows : 1fr;
    }

    gameboard.active{
      z-index:2;
    }

    gameboard > container{
      display : grid;
      grid-template-columns : 1fr;
      grid-template-rows : 1fr;
    }

    #grille {
      display: inline-flex;
      margin: auto;
    }

    .ligne {
      display: grid;
    }

    .cell {
      height: 40px;
      width: 40px;
      border: 1px solid black;
      background:lightgray;
      transition:0.2s;
    }

    .cell.active {
      transform:rotateY(360deg);
      background:darkslategray;
    }

    #player{
      height:100%;
      width:100%;
      background:red;
    }
  ")
?>
