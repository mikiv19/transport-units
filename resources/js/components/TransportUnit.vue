<template>
  <div class="container">
    <div class="tabs">
      <button 
        @click="setActiveTab('truck')" 
        :class="{ active: activeTab === 'truck' }"
      >
        Trucks ({{ truckCount }})
      </button>
      <button 
        @click="setActiveTab('trailer')" 
        :class="{ active: activeTab === 'trailer' }"
      >
        Trailers ({{ trailerCount }})
      </button>
    </div>

    <div class="search-box">
      <input
        v-model="searchQuery"
        placeholder="Search units..."
        @input="debouncedSearch"
        class="search-input"
      />
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
        class="load-more"
      >
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
          class="form-input"
        >
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
    async fetchData(reset = false) {
  this.loading = true;
  try {
    const page = reset ? 1 : this.currentPage + 1; // Fix pagination logic

    const response = await axios.get('/api/transport-units', {
      params: {
        type: this.activeTab,
        search: this.searchQuery,
        page: page, // Ensure correct page is sent
      }
    });

    if (reset) {
      this.transportUnits = response.data.data;
      this.currentPage = 1;
    } else {
      this.transportUnits = [...this.transportUnits, ...response.data.data];
      this.currentPage = page; // Update currentPage only when loading more
    }

    // Update counts from meta
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
      this.newUnit = { name: '', type: 'truck' }; // Reset form
      this.fetchData(true); // Refresh the list
    } catch (error) {
      console.error('Create error:', error);
      alert('Error creating unit. Please check the console for details.');
    }
  },

  // REMOVE the updateCounts method completely
  // ... rest of the methods remain the same
},
mounted() {
  this.fetchData(true); // Only call fetchData here
},
};
</script>

<style scoped>
.container {
  max-width: 800px;
  margin: 2rem auto;
  padding: 1rem;
}

.tabs {
  display: flex;
  gap: 1rem;
  margin-bottom: 2rem;
}

.tabs button {
  padding: 0.5rem 1rem;
  border: 1px solid #ccc;
  border-radius: 4px;
  cursor: pointer;
  background: white;
}

.tabs button.active {
  background: #3b82f6;
  color: white;
  border-color: #3b82f6;
}

.search-box {
  margin-bottom: 1.5rem;
}

.search-input {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.unit-list {
  margin-bottom: 1rem;
}

.unit-item {
  display: flex;
  justify-content: space-between;
  padding: 0.75rem;
  margin-bottom: 0.5rem;
  background: #f8fafc;
  border-radius: 4px;
}

.load-more {
  width: 100%;
  padding: 0.5rem;
  background: #e2e8f0;
  border: 1px solid #cbd5e1;
  border-radius: 4px;
  cursor: pointer;
}

.create-form {
  margin-top: 2rem;
  padding: 1.5rem;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.form-group {
  margin-bottom: 1rem;
}

.form-input, .form-select {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.submit-btn {
  background: #10b981;
  color: white;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.loading {
  text-align: center;
  padding: 1rem;
  color: #64748b;
}
</style>