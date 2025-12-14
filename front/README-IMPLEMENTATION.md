# Course Platform Frontend - Vue.js Implementation

## Overview

This is a complete Vue 3 + TypeScript frontend application for the Course Learning Management System with separate Admin and Student Portal interfaces.

## Features Implemented

### Admin Panel (`/admin`)
- **Authentication**: Admin login with JWT
- **Dashboard**: Analytics overview with key metrics
- **Course Management**: Full CRUD for courses
- **User Management**: View, update, and manage users
- **Enrollments**: View and manage student enrollments
- **Reviews**: Approve/reject student reviews
- **Categories**: Manage course categories

### Student Portal (`/`)
- **Authentication**: Register, login, password reset
- **Home Page**: Featured courses and category browsing
- **Course Discovery**: Browse, search, and filter courses
- **Course Details**: View course information and enroll
- **My Courses**: View enrolled courses with progress
- **Learning Interface**: Complete lessons, track progress
- **Profile Management**: Update profile and change password

## Tech Stack

- **Vue 3** with Composition API
- **TypeScript** for type safety
- **Pinia** for state management
- **Vue Router** for routing
- **Axios** for API calls
- **Vite** for build tooling

## Project Structure

```
src/
├── layouts/           # Layout components
│   ├── AdminLayout.vue
│   └── PortalLayout.vue
├── views/            # Page components
│   ├── admin/        # Admin pages
│   │   ├── Login.vue
│   │   ├── Dashboard.vue
│   │   ├── Courses.vue
│   │   ├── Users.vue
│   │   ├── Enrollments.vue
│   │   ├── Reviews.vue
│   │   └── Categories.vue
│   └── portal/       # Student portal pages
│       ├── Login.vue
│       ├── Register.vue
│       ├── Home.vue
│       ├── Courses.vue
│       ├── CourseDetail.vue
│       ├── MyCourses.vue
│       ├── Learn.vue
│       ├── Profile.vue
│       └── CategoryCourses.vue
├── stores/           # Pinia stores
│   ├── adminAuth.ts
│   └── portalAuth.ts
├── services/         # API services
│   ├── api.ts
│   ├── adminService.ts
│   └── portalService.ts
├── router/           # Vue Router configuration
│   └── index.ts
└── App.vue           # Root component
```

## Setup Instructions

### 1. Install Dependencies

```bash
cd front
npm install
```

### 2. Configure Environment

Create a `.env` file in the `front` directory:

```env
VITE_API_BASE_URL=http://localhost:8080
```

### 3. Run Development Server

```bash
npm run dev
```

The application will be available at `http://localhost:5173`

### 4. Build for Production

```bash
npm run build
```

## API Integration

The application is fully integrated with the CodeIgniter backend API:

### Admin API Endpoints
- `POST /admin/auth/login` - Admin login
- `GET /admin/courses` - List courses
- `GET /admin/users` - List users
- `GET /admin/enrollments` - List enrollments
- `GET /admin/reviews` - List reviews
- `GET /admin/categories` - List categories
- `GET /admin/analytics/overview` - Dashboard analytics

### Portal API Endpoints
- `POST /portal/auth/register` - Student registration
- `POST /portal/auth/login` - Student login
- `GET /portal/courses` - Browse courses
- `GET /portal/courses/:id` - Course details
- `POST /portal/courses/:id/enroll` - Enroll in course
- `GET /portal/my-courses` - My enrolled courses
- `GET /portal/courses/:id/curriculum` - Course curriculum
- `GET /portal/lessons/:id` - Get lesson content
- `POST /portal/lessons/:id/complete` - Mark lesson complete
- `GET /portal/profile` - Get user profile
- `PUT /portal/profile` - Update profile

## Authentication

### Admin
- Navigate to `/admin/login`
- Login with admin credentials
- JWT token stored in `localStorage` as `admin_token`
- Auto-redirect on authenticated routes

### Student Portal
- Navigate to `/register` to create account
- Navigate to `/login` to sign in
- JWT token stored in `localStorage` as `portal_token`
- Auto-redirect on authenticated routes

## Route Protection

Routes are protected using Vue Router navigation guards:

- **Admin routes** (`/admin/*`): Require admin authentication
- **Student routes** (My Courses, Profile, Learn): Require student authentication
- **Guest routes** (Login, Register): Redirect to home if already authenticated

## State Management

### Admin Auth Store
- Manages admin authentication state
- Stores user data and JWT token
- Provides login/logout methods

### Portal Auth Store
- Manages student authentication state
- Stores user data and JWT token
- Provides register/login/logout methods
- Fetches user profile data

## Styling

The application uses utility-first CSS styling:
- Tailwind-like classes for rapid UI development
- Responsive design for mobile, tablet, and desktop
- Consistent color scheme (blue primary, gray secondary)
- Shadow and hover effects for better UX

## Features Breakdown

### Admin Dashboard
- Total students count
- Total instructors count
- Published courses count
- Total enrollments count
- Quick action buttons

### Course Management
- List all courses with filters
- View course details
- Create new courses
- Edit existing courses
- Delete courses
- Update course status (draft/published/archived)

### Student Course Discovery
- Featured courses section
- Category-based browsing
- Search functionality
- Filter by level (beginner/intermediate/advanced)
- Course ratings and enrollment count

### Learning Experience
- Sidebar curriculum navigation
- Video and article lesson support
- Progress tracking per lesson
- Mark lessons as complete
- Next lesson navigation
- Visual progress indicators

### Profile Management
- Update personal information
- Change password
- Upload profile picture
- View user statistics

## Development Tips

### Adding New Pages

1. Create the component in `src/views/admin/` or `src/views/portal/`
2. Add the route in `src/router/index.ts`
3. Add navigation link in the appropriate layout

### Adding New API Services

1. Add the service method in `src/services/adminService.ts` or `portalService.ts`
2. Use the service in your component:

```typescript
import { adminService } from '@/services/adminService'

const data = await adminService.getCourses()
```

### Working with Stores

```typescript
import { usePortalAuthStore } from '@/stores/portalAuth'

const authStore = usePortalAuthStore()

// Access state
console.log(authStore.user)
console.log(authStore.isAuthenticated)

// Call actions
await authStore.login(email, password)
authStore.logout()
```

## Browser Compatibility

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## Known Limitations

1. File uploads require proper backend configuration
2. Some pages use lazy loading for better performance
3. Basic styling - can be enhanced with additional CSS frameworks

## Future Enhancements

- Quiz taking interface
- Real-time notifications
- Discussion forums
- Advanced analytics charts
- Course creation wizard
- Bulk operations for admin
- Export/import functionality

## Troubleshooting

### CORS Issues
- Ensure backend allows requests from `http://localhost:5173`
- Configure CORS in CodeIgniter backend

### Authentication Issues
- Clear localStorage tokens
- Check API base URL in `.env`
- Verify JWT token expiration

### API Connection Issues
- Ensure backend is running on port 8080
- Check network tab in browser DevTools
- Verify API endpoints match backend routes

## Support

For issues or questions, refer to:
- Vue 3 Documentation: https://vuejs.org/
- Pinia Documentation: https://pinia.vuejs.org/
- Vue Router Documentation: https://router.vuejs.org/
