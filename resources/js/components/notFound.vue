<script setup>
    import { reactive, ref } from "vue"
    import { useRouter } from "vue-router"

</script>
<template>
    <div class="adminx-container d-flex justify-content-center align-items-center">
      <div class="page-error">
        <h1>Page not found</h1>

        <p class="text-muted mb-5">
          Oops... the page you requested could not be found.
        </p>
        <div class="space-x-2">
          <a href="#" class="btn btn-light btn-sm">Report error</a>
          <a href="/dashboard" class="btn btn-primary btn-sm"><span>Return to the dashboard</span></a>
        </div>
      </div>
    </div>
</template>