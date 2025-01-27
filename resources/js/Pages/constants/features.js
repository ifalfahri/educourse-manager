import { 
    AcademicCapIcon, 
    UserGroupIcon, 
    ChartBarIcon,
    BookOpenIcon,
    DocumentTextIcon,
    AdjustmentsVerticalIcon 
} from '@heroicons/vue/24/outline';


export const features = [
    {
        name: 'Course Management',
        description: 'Create, update, and manage courses. Track course status and enrollment counts.',
        icon: BookOpenIcon
    },
    {
        name: 'Student Management',
        description: 'Keep track of student information and their course enrollments efficiently.',
        icon: UserGroupIcon
    },
    {
        name: 'Advanced Reports',
        description: 'Generate detailed reports in Excel and PDF formats for better insights.',
        icon: ChartBarIcon
    },
    {
        name: 'Enrollment Tracking',
        description: 'Monitor student enrollments and course progress in real-time.',
        icon: AcademicCapIcon
    },
    {
        name: 'Document Management',
        description: 'Manage course materials and student documents securely.',
        icon: DocumentTextIcon
    },
    {
        name: 'Customizable Settings',
        description: "Flexible configuration options to match your institution's needs.",
        icon: AdjustmentsVerticalIcon
    }
];