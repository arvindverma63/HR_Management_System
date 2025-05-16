<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HR Dashboard - New Workmen Form</title>
  <script src="https://cdn.tailwindcss.com"></script>
    <style>
    /* Custom scrollbar */
    ::-webkit-scrollbar {
      width: 6px;
    }
    ::-webkit-scrollbar-track {
      background: #f1f1f1;
    }
    ::-webkit-scrollbar-thumb {
      background: #134a6b;
      border-radius: 4px;
    }
    ::-webkit-scrollbar-thumb:hover {
      background: #0f3a54;
    }
    /* Smooth transitions */
    .transition-all {
      transition: all 0.3s ease;
    }
    /* Hide sidebar on mobile */
    #sidebar {
      transition: transform 0.3s ease;
    }
    @media (max-width: 768px) {
      #sidebar {
        transform: translateX(-100%);
      }
      #sidebar.open {
        transform: translateX(0);
      }
    }
    /* Custom color classes for buttons and sidebar */
    .bg-custom-blue {
      background-color: #134a6b;
    }
    .hover\:bg-custom-blue-dark:hover {
      background-color: #0f3a54;
    }
    .focus\:ring-custom-blue:focus {
      --tw-ring-color: #134a6b;
    }
  </style>
  </head>
