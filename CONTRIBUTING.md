# Contributing Guide

Thank you for your interest in contributing to the Daikin ONECTA plugin! This document provides guidelines for contributing to the project.

## 🚀 How to Contribute

### Reporting a Bug

If you find a bug, please create an [issue](https://github.com/ThibautTrarbach/daikinRCCloud/issues) with the following information:

- **Bug Description**: Clear and concise description
- **Steps to Reproduce**: Detailed steps to reproduce the problem
- **Expected Behavior**: What should happen
- **Current Behavior**: What actually happens
- **Environment**:
  - Jeedom version
  - Plugin version
  - Daemon version
  - Operating system
- **Logs**: Relevant log excerpts (without sensitive information)
- **Screenshots**: If applicable

### Proposing a Feature

To propose a new feature:

1. First check that it doesn't already exist in the [issues](https://github.com/ThibautTrarbach/daikinRCCloud/issues)
2. Create a new issue with the "enhancement" label
3. Clearly describe the feature and its usefulness

### Contributing Code

1. **Fork** the project
2. Create a **branch** for your feature (`git checkout -b feature/AmazingFeature`)
3. **Commit** your changes (`git commit -m 'Add some AmazingFeature'`)
4. **Push** to the branch (`git push origin feature/AmazingFeature`)
5. Open a **Pull Request**

### Code Standards

- Follow existing PHP code conventions
- Add comments for complex code
- Test your changes before submitting
- Ensure the code works with daemon version 2.0.0 or higher

### Translations

To contribute to translations:

1. Modify files in `core/i18n/`
2. Use the standard JSON format
3. Test that translations display correctly in the interface

## 📝 Project Structure

```
daikinRCCloud/
├── core/
│   ├── ajax/          # AJAX files
│   ├── class/         # Main PHP classes
│   ├── i18n/          # Translation files
│   └── php/           # Utility PHP files
├── desktop/
│   ├── js/            # JavaScript files
│   └── php/           # Interface PHP files
├── plugin_info/       # Plugin information and configuration
├── resources/         # Installation scripts
└── docs/              # Documentation (if applicable)
```

## ✅ Checklist Before Submitting

- [ ] Code follows project conventions
- [ ] Tests pass (if applicable)
- [ ] Documentation is up to date
- [ ] Translations are complete (if applicable)
- [ ] Logs do not contain sensitive information
- [ ] Code is compatible with daemon version 2.0.0 or higher

## 📞 Contact

For any questions, feel free to:
- Open an issue on GitHub
- Contact the maintainers via the Jeedom forum

Thank you for your contribution! 🎉
