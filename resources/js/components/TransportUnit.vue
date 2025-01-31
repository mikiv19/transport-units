<template>
  <div class="container">
    <div class="tabs">
      <button 
        @click="setActiveTab('truck')" 
        :class="{ active: activeTab === 'truck' }">
        Trucks ({{ truckCount }})
      </button>
      <button 
        @click="setActiveTab('trailer')" 
        :class="{ active: activeTab === 'trailer' }">
        Trailers ({{ trailerCount }})
      </button>
    </div>

    <div class="search-box">
      <input
        v-model="searchQuery"
        placeholder="Search units..."
        @input="debouncedSearch"
        class="search-input"/>
    </div>

    <div v-if="loading" class="loading">Loading...</div>
    
    <div v-else>
      <div class="unit-list">
        <div v-for="unit in transportUnits" :key="unit.id" class="unit-item">
          <span class="unit-name">{{ unit.name }}</span>
          <span class="unit-type">{{ unit.type_string }}</span>
        </div>
      </div>

      <button 
        v-if="hasMore" 
        @click="loadMore"
        class="load-more">
        Load More
      </button>
    </div>

    <form @submit.prevent="createUnit" class="create-form">
      <h3>Create New Unit</h3>
      <div class="form-group">
        <input
          v-model="newUnit.name"
          placeholder="Unit name"
          required
          class="form-input">
      </div>
      <div class="form-group">
        <select v-model="newUnit.type" required class="form-select">
          <option value="truck">Truck</option>
          <option value="trailer">Trailer</option>
        </select>
      </div>
      <button type="submit" class="submit-btn">Create Unit</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';
import _ from 'lodash';

export default {
  name: 'TransportUnits',
  data() {
    return {
      activeTab: 'truck',
      searchQuery: '',
      transportUnits: [],
      currentPage: 1,
      lastPage: 1,
      truckCount: 0,
      trailerCount: 0,
      loading: false,
      newUnit: { 
        name: '', 
        type: 'truck' 
      }
    };
  },
  computed: {
    hasMore() {
      return this.currentPage < this.lastPage;
    }
  },
  methods: {
    setActiveTab(type) {
      this.activeTab = type;
    },

    debouncedSearch: _.debounce(function() {
      this.fetchData(true);
    }, 300),

    async fetchData(reset = false) {
      this.loading = true;
      try {
        const page = reset ? 1 : this.currentPage + 1;
        const response = await axios.get('/api/transport-units', {
          params: {
            type: this.activeTab,
            search: this.searchQuery,
            page: page,
          }
        });

        if (reset) {
          this.transportUnits = response.data.data;
          this.currentPage = 1;
        } else {
          this.transportUnits = [...this.transportUnits, ...response.data.data];
          this.currentPage = page;
        }

        this.truckCount = response.data.meta.total_trucks;
        this.trailerCount = response.data.meta.total_trailers;
        this.lastPage = response.data.meta.last_page;

      } catch (error) {
        console.error('Fetch error:', error);
        alert('Error loading data');
      } finally {
        this.loading = false;
      }
    },

    async createUnit() {
      try {
        await axios.post('/api/transport-units', this.newUnit);
        this.newUnit = { name: '', type: 'truck' };
        this.fetchData(true);
      } catch (error) {
        console.error('Create error:', error);
        alert('Error creating unit. Please check the console for details.');
      }
    },
  },
  watch: {
    activeTab() {
      this.fetchData(true);
    },
  },
  mounted() {
    this.fetchData(true); 
  },
};
</script>

<style scoped>
@import '/resources/css/TransportUnits.css';
</style>