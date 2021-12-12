<template>
  <div class="text-center mt-5">
    <h1>Start the Crawl!</h1>
    <p class="lead">Enter a URL, select number of pages and hit Go</p>
    <form class="row g-3 d-flex justify-content-center" @submit.prevent="submit">
      <div class="col-auto">
        <input type="url" class="form-control" size="50" pattern="https://.*" id="url" required v-model="form.url"
               placeholder="Enter a URL e.g https://codisfy.com">
      </div>
      <div class="col-auto">
        <select class="form-select" aria-label="No. of pages" v-model="form.pages">
          <template v-for="(val, index) in options()">
            <option :value="val">{{ val }}</option>
          </template>
        </select>
      </div>
      <div class="col-auto">
        <button type="submit" :disabled="submitting" class="btn btn-primary mb-3">Go</button>
      </div>
    </form>
    <div v-if="submitting">
      <div class="spinner-border text-primary" role="status">

      </div>
      <div>Loading your results...</div>
    </div>
  </div>
</template>
<script>
import Default from '../Layout/Default';
import {Inertia} from '@inertiajs/inertia';

export default {
  layout: Default,
  props: {
    name: String
  },
  methods: {
    say () {
      return 'How are you?';
    },
    submit () {
      this.submitting = true;
      Inertia.post('/crawl', this.form);
    },
    options () {
      return [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
    }
  },
  data () {
    return {
      form: {
        url: '',
        pages: 5
      },
      submitting: false
    };
  }
};
</script>
