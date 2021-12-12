<template>
  <div class="text-center">
    <h1>Your Results</h1>
    <p class="lead">{{ url }}</p>
    <div class="w-75 mx-auto d-flex justify-content-center flex-column ">
      <table class="table">
        <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Page URL</th>
          <th scope="col">Status Code</th>
          <th scope="col">Images</th>
          <th scope="col">Links</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(data, index) in crawled_data" :key="index">
          <th scope="row">{{ index + 1 }}</th>
          <td>{{ data['url'] }}</td>
          <td>{{ data['code'] }}</td>
          <td>{{ data['images'].length }}</td>
          <td>{{ Object.keys(data['links']).length }}</td>
        </tr>
        </tbody>
      </table>
      <div class="other-stats">
        <p class="fs-3">Other Stats</p>
        <table class="table table-striped">
          <tbody>
          <tr>
            <th scope="row">Number of pages crawled</th>
            <td>{{ noOfPages }}</td>
          </tr>
          <tr>
            <th scope="row">Number of unique images</th>
            <td>{{ noOfUniqueImages }}</td>
          </tr>
          <tr>
            <th scope="row">Number of unique internal links</th>
            <td>{{ noOfLinks.internalLinks }}</td>
          </tr>
          <tr>
            <th scope="row">Number of unique external links</th>
            <td>{{ noOfLinks.externalLinks }}</td>
          </tr>
          <tr>
            <th scope="row">Average page load in seconds</th>
            <td>{{ avgLoadTime }}</td>
          </tr>
          <tr>
            <th scope="row">Average word count</th>
            <td>{{ avgWordCount }}</td>
          </tr>
          <tr>
            <th scope="row">Average title length</th>
            <td>{{ avgTitleLength }}</td>
          </tr>
          </tbody>
        </table>
        <ilink class="btn btn-lg btn-primary" href="/home">Crawl another page.</ilink>

      </div>
    </div>
  </div>
</template>
<script>
import Default from '../Layout/Default';

export default {
  layout: Default,
  props: {
    crawled_data: {
      required: true
    },
    url: {
      required: true
    }
  },
  computed: {
    noOfPages () {
      return this.crawled_data.length;
    },
    noOfUniqueImages () {
      let imageCount = 0;
      let uniqueImage = {};
      for (let data of this.crawled_data) {
        for (let image of (data['images'] || [])) {
          if (!uniqueImage[image]) {
            imageCount++;
            uniqueImage[image] = 1;
          }
        }
      }
      return imageCount;
    },
    noOfLinks () {
      let internalLinks = 0;
      let externalLinks = 0;
      let uniqueLinks = {};
      for (let data of this.crawled_data) {
        for (let url in (data['links'] || {})) {
          if (!uniqueLinks[url]) {
            if (data['links'][url]['internal']) {
              internalLinks++;
            }
            else {
              externalLinks++;
            }
            uniqueLinks[url] = 1;
          }
        }
      }
      return {
        internalLinks, externalLinks
      };
    },
    avgLoadTime () {
      let totalLoadTime = 0;
      for (let data of this.crawled_data) {
        totalLoadTime += data['load_time'] || 0;
      }
      return (totalLoadTime / (this.noOfPages || 1)).toFixed(2);
    },
    avgWordCount () {
      let totalWordCount = 0;
      for (let data of this.crawled_data) {
        totalWordCount += data['word_count'] || 0;
      }
      return (totalWordCount / (this.noOfPages || 1)).toFixed(1);
    },
    avgTitleLength () {
      let totalWordCount = 0;
      for (let data of this.crawled_data) {
        totalWordCount += (data['title'] || '').match(/(\w+)/g)?.length || 0;
      }
      return (totalWordCount / (this.noOfPages || 1)).toFixed(1);
    }
  }
};
</script>
