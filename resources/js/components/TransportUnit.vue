<template>
 
  <div class="container">
    <img class="logo" fetchpriority="high" src="https://mypallet.io/wp-content/uploads/2022/03/logo-default.svg">
    <form @submit.prevent="createUnit" class="create-form">
      <h3>Create New Unit</h3>
      <div class="form-group">
        <input
          v-model="newUnit.name"
          placeholder="New unit name..."
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
    <div class="tabs">
      <button 
        @click="setActiveTab('truck')" 
        :class="['tab-btn', { active: activeTab === 'truck', inactive: activeTab !== 'truck' }]">
        Trucks ({{ truckCount }})
      </button>
      <button 
        @click="setActiveTab('trailer')" 
        :class="['tab-btn', { active: activeTab === 'trailer', inactive: activeTab !== 'trailer' }]">
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
    
    <div v-else class="unit-list">
      <div class="unit-list-sub">
        <div v-for="unit in transportUnits" :key="unit.id" class="unit-item">
          <span class="unit-name">{{ unit.name }}</span>
          <span class="unit-type">{{ unit.type_string }}</span>
        </div>
      </div>

      <div class="pagination-controls">
        <button 
          @click="previousPage" 
          :disabled="currentPage === 1"
          class="page-btn">
          Previous
        </button>
        <span class="page-info">Page {{ currentPage }} of {{ lastPage }}</span>
        <button 
          @click="nextPage" 
          :disabled="currentPage === lastPage"
          class="page-btn">
          Next
        </button>
      </div>
    </div>
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
        const page = reset ? 1 : this.currentPage;
        const response = await axios.get('/api/transport-units', {
          params: {
            type: this.activeTab,
            search: this.searchQuery,
            page: page,
          }
        });

        this.transportUnits = response.data.data;

        if (reset) {
          this.currentPage = 1;
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
    nextPage() {
      if (this.currentPage < this.lastPage) {
        this.currentPage++;
        this.fetchData(false);
      }
    },
    previousPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
        this.fetchData(false);
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