async function guiLoader(){
  return {
    loading : [
      {
        type:'div',
        prop:{id:'loading',class:'active'},
        childrens:[
          {type:'div',prop:{name:'1'}},
          {type:'div',prop:{name:'2'}},
          {type:'div',prop:{name:'progressBar'},childrens:[{type:'p',prop:{text:'..Loading..'}},{type:'div'}]},
          // {type:'div',prop:{name:'loadInfos'},childrens:[{type:'p',prop:{text:'..Loading..'}}]}
        ]
      }
    ],
    sign : [
      {
        type:'div',
        prop:{
          id:'sign'
        },
        childrens:[
          {
            type:'div',
            prop:{
              name:'accounts'
            },
            childrens:await getUserAccounts()
          }
        ]
      }
    ],
    box_addAccount : [
      {
        type:'div',
        prop:{
          id:'box',
          name:'addAccount'
        },
        childrens:[
          {
            type:'div',
            prop:{
              name:'header'
            },
            childrens:[
              {
                type:'div',
                prop:{
                  name:'box_close',
                  onclick:'closeBox()'
                }
              }
            ]
          },
          {
            type:'div',
            prop:{name:'fields'},
            childrens:[
              {
                type:'input',
                prop:{
                  placeholder:'host',
                  name:'host'
                }
              },
              {
                type:'input',
                prop:{
                  placeholder:'user',
                  name:'user'
                }
              },
              {
                type:'input',
                prop:{
                  placeholder:'password',
                  name:'password'
                }
              },
              {
                type:'input',
                prop:{
                  placeholder:'db name(facultative)',
                  name:'db_name'
                }
              },
              {
                type:'p',
                prop:{
                  name:'validate',
                  text:'validate',
                  onclick:'validateNewAccoutInfos(this.parentNode)'
                }
              }
            ]
          }
        ]
      }
    ],
    box_sql : [
      {
        type:'div',
        prop:{
          id:'box',
          name:"SQLQuerry"
        },
        childrens:[
          {
            type:'div',
            prop:{
              name:'header'
            },
            childrens:[
              {
                type:'div',
                prop:{
                  name:'box_close',
                  onclick:'closeBox()'
                }
              }
            ]
          },
          await dbsDropDown(),
          {
            type:'textarea'
          },
          {
            type:'p',
            prop:{
              name:'action',
              text:'Action',
              onclick:'makeQuerry(this)'
            }
          },
          {
            type:'div',
            prop:{
              name:'results',
              text:'..WAITING QUERRY..'
            }
          }
        ]
      }
    ]
  }
}

async function guiUpdate(){
  gui.box_sql[0].childrens[1] = await dbsDropDown();
}

async function getUserAccounts(){
  var accounts = await querry.getAccounts() , toReturn = [];
  for await(var e of accounts){
    var obj = {
      type:'div',
      prop:{
        class:'account',
        // name:'default',
        host:e.host,
        user:e.user
      },
      childrens:[
        {
          type:'div',
          prop:{
            name:'ico'
          },
          childrens:[
            {
              type:'p',
              prop:{text:'u'}
            }
          ]
        },
        {
          type:'div',
          prop:{name:'hosterInfos',text:'host : '+e.host+' & user : '+e.user+''}
        },
        {
          type:'div',
          prop:{
            name:'sign',
            onclick:'sign(this.parentNode)'
          },
          childrens:[
            {
              type:'p',
              prop:{text:'connect'}
            }
          ]
        }
      ]
    }
    toReturn.push(obj);
  }
  toReturn.push({
    type:'div',
    prop:{
      name:'addAccount',
      onclick:'addAccount()'
    },
    childrens:[
      {
        type:'p',
        prop:{
          text:'+'
        }
      }
    ]
  })
  return toReturn;
}

async function dbsDropDown(){
  let child = [];
  try{
    for await(var e of Object.keys(dbs)){
      child.push({type:'option',prop:{value:e,text:e}})
    }
  }catch(err){}
  return {type:'select',childrens:child}
}
