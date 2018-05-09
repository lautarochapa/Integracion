

const data = {
    branches: [],
    newBranch_name : "",
    newBranch_frequency : "",
    newBranch_km : "",
    Branch_to_delete : {},
    Branch_to_edit : {}, 
}


function eliminandolo(id){
    axios.get("/branches/" + id)
        .then((resp)=> {data.Branch_to_delete = resp.data})
        .catch((err)=>
            console.error(err.response.data)
        )
}
function deleteRamal(){
    axios.delete("/branches/" + data.Branch_to_delete.id)
        .then((resp)=>updateBranches())
        .catch((err)=>
            console.error(err.response.data)
        )
    data.Branch_to_delete = {};
}

function editandolo(id){
    axios.get("/branches/" + id)
    .then((resp)=> data.Branch_to_edit = resp.data)
    .catch((err)=>
        console.error(err.response.data)
    )
}
function updateRamal(name, frequency, km_value){
    axios.put("/branches/" + data.Branch_to_edit.id, {name:name, frequency:frequency, km_value:km_value})
        .then((resp)=>{updateBranches();
            data.Branch_to_edit = ""})
        .catch((err)=>
            console.error(err.response.data)
        )
        data.Branch_to_edit = {};
}



function agregarRamal(name, frequency, km_value){
    axios.post("/branches/",{name:name, frequency:frequency, km_value:km_value})
        .then((resp)=>{
           updateBranches();
            data.nuevoRamal = "";   
        })
        .catch((err)=>
            console.error(err.response.data)
        )
}


  window.addEventListener("load",()=> {
  updateBranches()
  new Vue({
  el: '#branches',
  data: data,
  methods:{
      deleteRamal : deleteRamal,
      agregarRamal : agregarRamal
  }
  })
})
  
function updateBranches(){
axios.get("/branches")
.then((resp)=> {
  data.branches = resp.data
})
.catch((err)=>
  console.error(err.response.data)
)

}





