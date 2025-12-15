# App Architecture

The uptime checker is designed to be simple, reliable, and extensible. It separates orchestration (Jobs) from execution (Services) and storage (Models). This is a Laravel 12 API application with a Vue.js frontend. The application follows conventional Laravel patterns on the backend and uses Vue Router for client-side routing on the frontend.

## Backend Architecture

### Framework & Structure
- **Laravel 12** API-only application
- Follows standard Laravel conventions and directory structure
- RESTful API endpoints defined in `routes/api.php`
- JSON responses for all API endpoints

### Base Implementation
- **Models**: `User` model with role-based access (admin/guest)
- **Controllers**: 
  - `AuthController` - handles registration, login, logout, and current user retrieval
  - `UserController` - provides user listing and detail endpoints
- **Middleware**: `EnsureUserIsAdmin` for admin-only route protection
- **Authentication**: Laravel Sanctum for API token authentication

### Conventions
- Resourceful controller methods only: `index`, `show`, `create`, `store`, `edit`, `update`, `destroy`
- Implicit route model binding where possible

## Frontend Architecture

### Framework & Tools
- **Vue 3** with Composition API (script setup style)
- **Vue Router** for client-side routing
- **Pinia** for state management (when needed)
- **Tailwind CSS** for styling

#### State Management
- **Pinia** stores for application-wide state (when needed)
- **Composables** for shared reactive functionality
- Local component state for component-specific data

#### Component Architecture
- Composition API with `<script setup>` syntax
- Import order: dependencies → components → layouts
- Use `@` alias for imports
- Prefer existing components from `components/ui/` before creating new ones
- Radix Vue components as foundation for new UI elements

#### Styling
- Tailwind CSS utility classes
- Neutral color palette for backgrounds (`bg-neutral-*`)
- No custom CSS unless absolutely necessary

## Development Guidelines

### Backend
- Always use API routes in `routes/api.php`
- Return JSON responses from all controllers
- Use implicit route model binding
- Reference adjacent controllers for patterns
- No requests, resources, or tests unless explicitly requested

### Frontend
- No TypeScript (leave existing TS alone if present)
- Use script setup style in Vue components
- Check `resources/js/components` before creating new components
- Use Radix Vue components when available
- Import components after all other imports, layouts after components
- Use `@` for all imports