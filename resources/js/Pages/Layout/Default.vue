<template>
  <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
    <header class="mb-auto">
      <div>
        <nav class="navbar navbar-expand-lg navbar-light">
          <div class="container-fluid">
            <ilink class="navbar-brand" href="/home">AgencyCrawler</ilink>
          </div>
        </nav>
      </div>
    </header>

    <main class="px-3 stretch">
      <slot/>
    </main>

    <footer class="mt-auto text-center">
      <p>AgencyCrawler. All rights reserved.</p>
    </footer>
  </div>

</template>
