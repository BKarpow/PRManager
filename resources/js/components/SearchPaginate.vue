<template>
  <div class="container mt-5">
    <div class="card shadow-sm">
      <div v-if="searchQuery.length > 3" class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Контроль термінів придатності</h5>
        <span class="badge bg-info">Всього: {{ pagination.total }}</span>
      </div>

      <div class="card-body">
        <div class="input-group mb-4">
          <input
            v-model="searchQuery"
            @input="resetAndFetch"
            type="text"
            class="form-control"
            placeholder="Почніть вводити назву продукту..."
          >
        </div>

        <div v-if="searchQuery.length > 3" class="table-responsive">
          <table class="table table-striped align-middle">
            <thead>
              <tr>
                <th>Назва продукту</th>
                <th>Дата закінчення</th>
                <th>Дійсний до (днів)</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in expiries" :key="item.id">
                <td class="fw-bold"><a :href="'/date/show/' + item.id">{{ item.product.name }}</a></td>
                <td>{{ formatDate(item.end) }}</td>
                <td>
                  <span :class="getStatusClass(item.end)">
                    {{ getDaysRemaining(item.end) }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <nav  v-if="pagination.last_page > 1 && searchQuery.length > 3" class="d-flex justify-content-center mt-4">
          <ul class="pagination">
            <li class="page-item" :class="{ disabled: pagination.current_page === 1 }">
              <a class="page-link" href="#" @click.prevent="fetchExpiries(pagination.current_page - 1)">Назад</a>
            </li>

            <li v-for="page in pagination.last_page" :key="page"
                class="page-item" :class="{ active: page === pagination.current_page }">
              <a class="page-link" href="#" @click.prevent="fetchExpiries(page)">{{ page }}</a>
            </li>

            <li class="page-item" :class="{ disabled: pagination.current_page === pagination.last_page }">
              <a class="page-link" href="#" @click.prevent="fetchExpiries(pagination.current_page + 1)">Вперед</a>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import axios from 'axios';

const searchQuery = ref('');
const expiries = ref([]);
const pagination = reactive({
  current_page: 1,
  last_page: 1,
  total: 0
});

const fetchExpiries = async (page = 1) => {
    if (searchQuery.value.lenght < 3) return;
  try {
    const response = await axios.get('/date/search', {
      params: {
        search: searchQuery.value,
        page: page // Передаємо номер сторінки на сервер
      }
    });

    // Оновлюємо дані та стан пагінації
    expiries.value = response.data.data;
    pagination.current_page = response.data.current_page;
    pagination.last_page = response.data.last_page;
    pagination.total = response.data.total;
  } catch (error) {
    console.error("Помилка:", error);
  }
};

const resetAndFetch = () => {
  fetchExpiries(1); // При новому пошуку скидаємо на 1 сторінку
};

// Допоміжні функції для красивого виводу
const formatDate = (date) => new Date(date).toLocaleDateString('uk-UA');

const getDaysRemaining = (date) => {
  const diff = new Date(date) - new Date();
  const days = Math.ceil(diff / (1000 * 60 * 60 * 24));
  return days > 0 ? `${days} дн.` : 'Термін вийшов';
};

const getStatusClass = (date) => {
  const diff = new Date(date) - new Date();
  const days = Math.ceil(diff / (1000 * 60 * 60 * 24));
  if (days <= 0) return 'badge bg-danger';
  if (days <= 5) return 'badge bg-warning text-dark';
  return 'badge bg-success';
};

// fetchExpiries();
</script>
