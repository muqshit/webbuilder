<!DOCTYPE html>
<html>
<head>
  <title>AI Builder ULTRA</title>
  <link rel="stylesheet" href="style.css">
  <script src="https://unpkg.com/monaco-editor@latest/min/vs/loader.js"></script>
</head>
<body>

<div class="app">

  <div class="sidebar">
    <h3>Projects</h3>
    <button onclick="loadProjects()">Refresh</button>
    <div id="projectList"></div>
  </div>

  <div class="main">

    <div class="topbar">
      <button onclick="toggleTheme()">🌙</button>
      <button onclick="saveProject()">💾</button>
      <button onclick="downloadZip()">📦</button>
      <button onclick="deploy()">🚀</button>
    </div>

    <div class="workspace">

      <div class="chat">
        <div id="chatBox"></div>

        <button onclick="aiAction('Make modern UI')">✨</button>
        <button onclick="aiAction('Add animation')">🎬</button>
        <button onclick="aiAction('Fix bugs')">🛠</button>

        <textarea id="prompt"></textarea>
        <button onclick="sendPrompt()">Send</button>
      </div>

      <div class="editor-preview">
        <div id="editor"></div>
        <iframe id="preview"></iframe>
      </div>

    </div>

  </div>

</div>

<script src="script.js"></script>
</body>
</html>
