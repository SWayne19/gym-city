<p align="center">
    <h1 align="center" style="font-weight: bold; color: #fbbf24;">GYM CITY MANAGEMENT SYSTEM</h1>
</p>

<p align="center">
    <text>A high-performance, modern Gym Subscription and Member Directory Management application built with Laravel, Inertia.js, and Vue 3.</text>
</p>

---

## Tech Stack & Core Ecosystem

Gym City is built using the **VILT Stack** with cutting-edge optimizations for performance, server-side filtering, and instant state management.

* **Backend Framework:** Laravel 13
* **Frontend Engine:** Vue 3 (Composition API with `<script setup>`)
* **Bridge Layer:** Inertia.js (Zero-API approach)
* **Styling Engine:** Tailwind CSS 3+
* **Database:** Postgres

---

## Core Features Built So Far

### 1. High-Performance Members Directory
* **Dynamic Server-Side Filtering:** Implemented via Laravel's conditional query clauses (`->when()`) for fast and lightweight memory execution.
* **Debounced Live Search:** UI inputs utilize `lodash/debounce` (300ms) to ensure minimal database overhead during real-time keystrokes.
* **Eager Loaded Subscriptions:** Multi-level Eloquent relation mappings to pull current active/expired plans instantly.

### 2. Dynamic Gym Pricing & Packages (CRUD)
* **Isolated Architecture:** Custom Form Request validation helpers (`StorePackageRequest` & `UpdatePackageRequest`) to decouple validation context from business logic.
* **Database Guard Integrity:** Safe cascade check that strictly blocks admins from accidently deleting operational gym plans that currently have active member subscriptions linked.
* **Context-Based Badges:** Intelligent duration categorization tiers (Short, Medium, and Long Term) directly integrated into the directory table view.

### 3. Sleek Admin Workspace & Shell Layout
* **Unified Left Form/Right Grid Layout:** Optimized administrative workflow allowing dual action inputs and reactive table sync without extra pagination reloads.
* **Interactive Notifications System:** Micro-interaction pop-up flash toasts dynamically syncing backend session variables via deep Vue watchers.
* **Secure Navigation Matrix:** Complete route condition validation highlight system checking active controllers on the sidebar shell.

---

## Local Development Installation

Follow these steps to set up the Gym City workspace locally on your system:

### 1. Clone the repository & Enter the directory
```bash
git clone <your-repository-url>
cd gym-city

