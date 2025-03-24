import {
    CopyIcon,
    LayoutDashboardIcon,
    UserPlusIcon
} from 'vue-tabler-icons';

import { useAuthStore } from '~/store/auth'
const { isAdministrator } = useAuthStore()

export interface menu {
    header?: string;
    title?: string;
    icon?: any;
    to?: string;
    chip?: string;
    chipColor?: string;
    chipVariant?: string;
    chipIcon?: string;
    children?: menu[];
    disabled?: boolean;
    type?: string;
    subCaption?: string;
}

const menuItemAdmin = isAdministrator
    ? [
        { header: 'Gestão de Usuários' },
        {
            title: 'Gerenciar Usuário',
            icon: UserPlusIcon,
            to: '/users'
        },
        {
            title: 'Gerenciar Perfil',
            icon: CopyIcon,
            to: '/profiles'
        },
    ] : []

const sidebarItem: menu[] = [
    { header: 'Home' },
    {
        title: 'Dashboard',
        icon: LayoutDashboardIcon,
        to: '/'
    },
    ...menuItemAdmin
];

export default sidebarItem;
