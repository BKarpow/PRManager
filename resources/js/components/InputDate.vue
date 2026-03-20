<template>
  <div class="date-input">

    <input
      ref="inputRef"
      v-model="displayValue"
      type="tel"
      :placeholder="placeholder"
      class="animate__animated animate__lightSpeedInLeft"
      :class="['date-input__field', { 'date-input__field--error': hasError }]"
      @input="handleInput"
      @blur="handleBlur"
      @keydown="handleKeydown"
      @focus="handleFocus"
    />
    <input  type="hidden" :name="nameInput" :value="validDate" />
    <div v-if="hasError" class="date-input__error">
      {{ errorMessage }}
    </div>
  </div>
</template>

<script>
export default {
  name: 'DateInput',
  props: {
    nameInput: {
        type: String,
        default: 'date',
    },
    label:{
        type: String,
        default: 'Вжити до: '
    },
    value: {
      type: String,
      default: ''
    },
    placeholder: {
      type: String,
      default: 'дд.мм.рр'
    }
  },
  data() {
    return {
      displayValue: '',
      hasError: false,
      errorMessage: '',
      isFocused: false,
      validDate: ""
    }
  },
  watch: {
    value: {
      immediate: true,
      handler(newVal) {
        if (!this.isFocused) {
          this.displayValue = this.formatDisplayValue(newVal)
          this.validDate =  this.displayValue.toString().split('.')
        this.validDate[2] = "20" + String(this.validDate[2])
        this.validDate = this.validDate.join('.')
        }
      }
    },
    validDate(n) {
        if (!this.hasError) {
            this.$emit('valid', n);
        }
    }
  },
  methods: {

    handleInput(event) {
      let value = event.target.value.replace(/[^\d.]/g, '')

      // Обмежуємо довжину
      if (value.length > 8) {
        value = value.slice(0, 8)
      }

      // Автоматично додаємо крапки
      value = this.autoFormat(value)
      this.displayValue = value

      // Валідація та emit
      this.validateAndEmit(value)
    },

    handleBlur() {
      this.isFocused = false
      const formatted = this.formatDate(this.displayValue)
      if (formatted && this.isValidDate(formatted)) {
        this.displayValue = this.formatDisplayValue(formatted)
        this.hasError = false
        this.$emit('input', formatted)
      } else if (this.displayValue) {
        this.hasError = true
        this.errorMessage = 'Невірний формат дати'
      }
    },

    handleFocus() {
      this.isFocused = true
      this.hasError = false
      // При фокусі показуємо тільки цифри
      this.displayValue = this.displayValue.replace(/\./g, '')
    },

    handleKeydown(event) {
      // Дозволяємо тільки цифри, крапки та служебні клавіши
      if (!this.isAllowedKey(event)) {
        event.preventDefault()
        return
      }

      // Обробка Backspace для зручного видалення
      if (event.key === 'Backspace' && this.displayValue.endsWith('.')) {
        event.preventDefault()
        this.displayValue = this.displayValue.slice(0, -1)
      }
    },

    isAllowedKey(event) {
      return (
        /^\d$/.test(event.key) || // цифри
        event.key === '.' || // крапка
        event.key === 'Backspace' ||
        event.key === 'Delete' ||
        event.key === 'Tab' ||
        event.key === 'ArrowLeft' ||
        event.key === 'ArrowRight' ||
        event.key === 'Home' ||
        event.key === 'End'
      )
    },

    autoFormat(value) {
      let result = value

      // Автоматично додаємо крапки після 2 та 4 цифр
      if (value.length > 2 && !value.includes('.')) {
        result = value.slice(0, 2) + '.' + value.slice(2)
      }
      if (value.length > 5 && value.split('.').length === 2) {
        const parts = value.split('.')
        result = parts[0] + '.' + parts[1].slice(0, 2) + '.' + parts[1].slice(2)
      }

      return result
    },

    validateAndEmit(value) {
      const formatted = this.formatDate(value)
      if (formatted && this.isValidDate(formatted)) {
        this.hasError = false
        this.validDate =  formatted.split('.')
        this.validDate[2] = "20" + String(this.validDate[2])
        this.validDate = this.validDate.join('.')
        this.$emit('valid', String(this.validDate))
      } else {
        this.hasError = value.length >= 6
        this.errorMessage = 'Невірна дата'
      }
    },

    formatDate(input) {
      if (!input) return ''

      const clean = input.replace(/\./g, '')
      if (clean.length < 6) return ''

      const day = clean.slice(0, 2)
      const month = clean.slice(2, 4)
      const year = clean.slice(4, 6)

      return `${day.padStart(2, '0')}.${month.padStart(2, '0')}.${year}`
    },

    formatDisplayValue(dateString) {
      if (!dateString) return ''
      return dateString
    },

    isValidDate(dateString) {
      console.debug('dateString ', dateString);
        if (!dateString || dateString.length !== 8) return false

      const [day, month, year] = dateString.split('.').map(Number)
      console.debug('day, month, year ', [day, month, year]);
      const fullYear = 2000 + year // припускаємо 21 століття
      console.debug('fullYear ', fullYear);

      // Перевірка базової валідності
      if (month < 1 || month > 12) return false
      if (day < 1 || day > 31) return false

      // Перевірка конкретних місяців
      const daysInMonth = new Date(fullYear, month, 0).getDate()
      console.debug('daysInMonth ', daysInMonth);
      return day <= daysInMonth
    },

    focus() {
        console.debug('Focused!');
      this.$refs.inputRef.focus()
    },

    blur() {
      this.$refs.inputRef.blur()
    }
  },
  mounted() {
    this.validateAndEmit(this.value);
  }
}
</script>

<style scoped>
.date-input {
  position: relative;
}

.date-input__field {
  width: 100%;
  background: inherit;
  padding: 0.1rem .2rem;
  border: 1px solid #092c7e;
  border-radius: 4px;
  font-size: 2rem;
  font-weight: bold;
  color: inherit;
  transition: border-color 0.8s;
}

.date-input__field:focus {
  outline: none;
  border-color: #409eff;
}

.date-input__field--error {
  border-color: #f56c6c;
}

.date-input__error {
  color: #f56c6c;
  font-size: 12px;
  margin-top: 4px;
}
</style>
