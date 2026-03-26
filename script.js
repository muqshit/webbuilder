let htmlCode="", cssCode="", jsCode="";
let editor;
let messages=[{role:"system",content:"Return JSON: html, css, js"}];

require.config({ paths: { vs: "https://unpkg.com/monaco-editor@latest/min/vs" } });
require(["vs/editor/editor.main"], function () {
  editor = monaco.editor.create(document.getElementById("editor"), {
    value: "// code",
    language: "html",
    theme: "vs-dark"
  });
});

function addMessage(t){
  let d=document.createElement("div");
  d.className="message";
  d.innerText=t;
  chatBox.appendChild(d);
}

async function sendPrompt(){
  let p=prompt.value;
  addMessage("🧑 "+p);

  messages.push({role:"user",content:p});

  let res=await fetch("api.php",{
    method:"POST",
    headers:{"Content-Type":"application/json"},
    body:JSON.stringify({action:"generate",messages})
  });

  let data=await res.json();
  let content=data.choices[0].message.content;

  messages.push({role:"assistant",content});

  let parsed=JSON.parse(content);

  htmlCode=parsed.html;
  cssCode=parsed.css;
  jsCode=parsed.js;

  updatePreview();
  updateEditor();
  saveVersion();

  addMessage("🤖 Done!");
}

function updatePreview(){
  preview.srcdoc=`<style>${cssCode}</style>${htmlCode}<script>${jsCode}<\/script>`;
}

function updateEditor(){
  editor.setValue(`<style>${cssCode}</style>\n${htmlCode}\n<script>${jsCode}<\/script>`);
}

function toggleTheme(){
  document.body.classList.toggle("light");
}

function saveProject(){
  let arr=JSON.parse(localStorage.getItem("projects"))||[];
  arr.push({html:htmlCode,css:cssCode,js:jsCode});
  localStorage.setItem("projects",JSON.stringify(arr));
  loadProjects();
}

function loadProjects(){
  let list=document.getElementById("projectList");
  list.innerHTML="";
  let arr=JSON.parse(localStorage.getItem("projects"))||[];

  arr.forEach((p,i)=>{
    let d=document.createElement("div");
    d.className="project";
    d.innerText="Project "+(i+1);
    d.onclick=()=>{
      htmlCode=p.html; cssCode=p.css; jsCode=p.js;
      updatePreview(); updateEditor();
    };
    list.appendChild(d);
  });
}

function saveVersion(){
  let v=JSON.parse(localStorage.getItem("versions"))||[];
  v.push({html:htmlCode,css:cssCode,js:jsCode});
  localStorage.setItem("versions",JSON.stringify(v));
}

function aiAction(t){
  prompt.value=t;
  sendPrompt();
}

async function downloadZip(){
  let res=await fetch("download.php",{method:"POST",body:JSON.stringify({html:htmlCode,css:cssCode,js:jsCode})});
  let blob=await res.blob();
  let a=document.createElement("a");
  a.href=URL.createObjectURL(blob);
  a.download="site.zip";
  a.click();
}

window.onload=loadProjects;
