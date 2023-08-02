export default {
  // global theme for the app
  globalTheme: 'light', // light | dark

  // side menu theme, use global theme or custom
  menuTheme: 'global', // global | light | dark

  // toolbar theme, use global theme or custom
  toolbarTheme: 'global', // global | light | dark

  // show toolbar detached from top
  isToolbarDetached: false,

  // wrap pages content with a max-width
  isContentBoxed: false,

  // application is right to left
  isRTL: true,
  primaryColor: '#014c4f',

  // dark theme colors
  dark: {
    background: '#25293C',
    surface: '#111b27',
    primary: '#7367F0',
    secondary: '#A8AAAE',
    accent: '#82B1FF',
    error: '#EA5455',
    info: '#00CFE8',
    success: '#28C76F',
    warning: '#FF9F43'
  },

  // light theme colors
  light: {
    background: '#ffffff',
    surface: '#f2f5f8',
    primary: '#014c4f',
    secondary: '#a0b9c8',
    accent: '#048ba8',
    error: '#ef476f',
    info: '#2196F3',
    success: '#06d6a0',
    warning: '#ffd166'
  }
}
