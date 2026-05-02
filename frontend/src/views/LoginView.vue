<script setup lang="ts">
import { computed, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuth } from '../services/auth'
import { getErrorMessage } from '../services/dashboard'

const auth = useAuth()
const router = useRouter()
const route = useRoute()

const email = ref('')
const password = ref('')
const errorMessage = ref('')
const successMessage = ref('')

const loading = computed(() => auth.isLoading.value)
const canSubmit = computed(() => email.value.trim() !== '' && password.value.trim() !== '' && !loading.value)

async function handleSubmit() {
  if (!canSubmit.value) return

  errorMessage.value = ''
  successMessage.value = ''

  try {
    await auth.login(email.value.trim(), password.value)
    await router.push((route.query.redirect as string) || '/')
  } catch (error) {
    errorMessage.value = getErrorMessage(error, 'Unable to sign in. Please check your email and password.')
  }
}
</script>

<template>
  <main class="login-page">
    <section class="login-card">
      <div class="brand">
        <p class="eyebrow">Welcome back</p>
        <h1>Sign in to continue</h1>
      </div>

      <form class="login-form" @submit.prevent="handleSubmit">
        <label>
          <span>Email</span>
          <input v-model="email" type="email" name="email" placeholder="you@example.com" autocomplete="email" />
        </label>

        <label>
          <span>Password</span>
          <input
            v-model="password"
            type="password"
            name="password"
            placeholder="••••••••"
            autocomplete="current-password"
          />
        </label>

        <button type="submit" :disabled="!canSubmit">
          {{ loading ? 'Signing in...' : 'Login' }}
        </button>

        <p v-if="errorMessage" class="message error">{{ errorMessage }}</p>
      </form>
    </section>
  </main>
</template>

<style scoped>
.login-page {
  min-height: 100vh;
  display: grid;
  place-items: center;
  padding: 24px;
  background:
    radial-gradient(circle at top, rgba(99, 102, 241, 0.18), transparent 40%),
    linear-gradient(180deg, #0f172a 0%, #111827 100%);
}

.login-card {
  width: min(100%, 420px);
  padding: 32px;
  border: 1px solid rgba(255, 255, 255, 0.08);
  border-radius: 24px;
  background: rgba(15, 23, 42, 0.78);
  backdrop-filter: blur(16px);
  box-shadow: 0 24px 80px rgba(15, 23, 42, 0.45);
  color: #f8fafc;
}

.brand {
  margin-bottom: 28px;
}

.eyebrow {
  margin-bottom: 8px;
  font-size: 0.85rem;
  font-weight: 600;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  color: #818cf8;
}

h1 {
  margin: 0;
  font-size: 2rem;
  line-height: 1.2;
}

.subtitle {
  margin-top: 10px;
  color: rgba(226, 232, 240, 0.78);
}

.login-form {
  display: grid;
  gap: 16px;
}

label {
  display: grid;
  gap: 8px;
  font-size: 0.95rem;
  color: #e2e8f0;
}

input {
  width: 100%;
  padding: 14px 16px;
  border: 1px solid rgba(148, 163, 184, 0.28);
  border-radius: 14px;
  background: rgba(15, 23, 42, 0.9);
  color: #f8fafc;
  outline: none;
  transition: border-color 0.2s, box-shadow 0.2s;
}

input::placeholder {
  color: rgba(148, 163, 184, 0.72);
}

input:focus {
  border-color: #818cf8;
  box-shadow: 0 0 0 4px rgba(129, 140, 248, 0.18);
}

button {
  margin-top: 4px;
  padding: 14px 16px;
  border: 0;
  border-radius: 14px;
  background: linear-gradient(135deg, #6366f1, #8b5cf6);
  color: white;
  font-weight: 700;
  cursor: pointer;
  transition: transform 0.2s ease, opacity 0.2s ease;
}

button:hover:not(:disabled) {
  transform: translateY(-1px);
}

button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.message {
  margin: 0;
  font-size: 0.92rem;
}

.error {
  color: #fca5a5;
}

.success {
  color: #86efac;
}
</style>
