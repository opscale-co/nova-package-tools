# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel Nova package development tool that extends Spatie's Laravel Package Tools with Nova-specific functionality. It provides an elegant way to build Laravel Nova packages by adding support for registering Nova resources through a fluent API.

## Key Architecture

### Core Classes

1. **NovaPackage** (`src/NovaPackage.php`): Extends Spatie's Package class with Nova-specific features via the HasResources trait.

2. **NovaPackageServiceProvider** (`src/NovaPackageServiceProvider.php`): Abstract service provider that extends Spatie's PackageServiceProvider, adding Nova resource processing capabilities.

3. **Traits**:
   - `HasResources` (`src/Concerns/Package/HasResources.php`): Adds methods to register Nova resources
   - `ProcessResources` (`src/Concerns/PackageServiceProvider/ProcessResources.php`): Handles the actual registration of resources with Laravel Nova

### Package Structure

The package follows a trait-based architecture where functionality is separated into concerns:
- Package configuration methods are in `src/Concerns/Package/`
- Service provider processing methods are in `src/Concerns/PackageServiceProvider/`

## Unit Testing

- **Type Error Checking**: After executing tests, verify there are no errors or warnings in the generated code
- **Test Success Requirement**: All tests must pass before code is considered ready
- **Concrete Test Requirements**: Abstract classes shouldn't have tests, only concrete classes 
- **Coverage Requirements**: All actions and services must have tests
- **Test Location**: Follow Laravel testing conventions
- **Static Context Priority**: Always try to test with static context using fixtures
- **Dynamic Context Fallback**: If static fixtures aren't possible, create dynamic context in `/tmp`
- **Cleanup Requirement**: Always remove temporary files after test execution
- **Fixture Location**: Store fixtures in `tests/fixtures` or appropriate test directories
- **Comprehensive Testing**: All services and actions should have tests covering both positive and negative cases

## Publishing

When asked to publish, verify the following requirements:

### 1. Code Quality
- **Linting**: Run `npm run lint` and ensure no errors
- **Testing**: Run `npm run test` and ensure all tests pass
- **Coverage**: Generate coverage report with `npm run test:coverage`
- **Coverage Threshold**: Ensure coverage meets minimum requirements (typically 80%)

### 2. Documentation
- **README Updates**: Verify and update the README file:
  - **Installation Section**: Ensure installation steps are current and accurate
  - **Usage Section**: Update with latest usage examples and commands
  - **Features Section**: Document all available features
  - **Configuration**: Include any configuration requirements
  - **Dependencies**: List all required and important dependencies

## AI Guidelines

- **Folder Protection**: Never delete folders, except those inside `/tmp`
- **Structure Compliance**: Never create folders outside the provided folder structure
- **Domain Creation Restrictions**: Never create new entities, enums, or migrations (creation is human-only)
- **Domain Modification Allowed**: Can modify existing entities, enums, or migrations when needed
- **Context Preservation**: Save a context session file after generating each feature in `.claude/` files
- **Definition of Done**: Code is complete only when all tests are passing
- **Version Control**: Force git commit before major changes involving many files
- **Linting Auto-Fix**: Always run `npm run fix` after generating code and before running `npm run lint` to automatically fix typing errors and formatting issues