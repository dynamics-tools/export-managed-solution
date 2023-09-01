# Export Managed Solution
This GitHub Action automates the process of exporting your managed solution from Dynamics 365 CRM. It's ideal for CI/CD pipelines where you want to automated retrieving your managed solution zip file.

## Features
- Allows you to define a new version number for this version of your solution
- Exports your managed solution and returns to you the file path of the exported zip file
- Supports authentication via client credentials - an application user in your Dynamics instance.

## Inputs
- `dynamics-url` - **Required**. The URL of your Dynamics instance. This is not the API URL, this is the URL you can find when you are using the application (ie -> yourorg.crm.dynamics.com not yourorg.api.crm.dynamics.com).
- `application-id` - **Required**. The Client ID of the application created in Microsoft Azure that connects to the application user
- `application-secret` - **Required**. The Client Secret of the application created in Microsoft Azure that connects to the application user
- `tenant-id` - **Required**. The Tenant ID of the application created in Microsoft Azure that connects to the application user
- `solution-name` - **Required**. The name of the solution you want to export
- `version-number` - **Required**. The version number you want to assign to this version of the solution

## Outputs
- `exported_file` - **Required**. The file path of your exported solution zip file

Best practice would be holding these values as repository secrets and then using them as secrets instead of plain values. Here is documentation about how to use secrets in GitHub Actions: https://docs.github.com/en/actions/security-guides/encrypted-secrets

## Usage

### Add Action to Workflow

To include this action in your GitHub Workflow, add the following step:

```yaml
    - name: Export Dynamics Solution
      id: export-dynamics-solution
      uses: dynamics-tools/export-managed-solution@v1
      with:
        dynamics-url: 'https://example.com' # alternatively secrets.DYNAMICS_URL
        application-id: '0000-0000-0000-0000' # alternatively secrets.APPLICATION_ID
        application-secret: '.akdjfoawiefe-~kdja' # alternatively secrets.APPLICATION_SECRET
        tenant-id: '0000-0000-0000-0000' # alternatively secrets.TENANT_ID
        solution-name: 'My Solution'
        version-number: '1.14.0.0'
```

### Example Workflow and how to use the output

```yaml
name: Publish Changes

on:
  push:
    branches:
      - main

jobs:
  publish:
    runs-on: ubuntu-latest
    steps:
    - name: Checkout code
      uses: actions/checkout@v2

    - name: Export Dynamics Solution
      id: export-dynamics-solution
      uses: dynamics-tools/export-managed-solution@v1
      with:
        dynamics-url: secrets.DYNAMICS_URL
        application-id: secrets.APPLICATION_ID
        application-secret: secrets.APPLICATION_SECRET
        tenant-id: secrets.TENANT_ID
        solution-name: 'My Solution'
        version-number: '1.14.0.0'

    - name: Use the output
      run: echo "The file path of the exported solution is ${{ steps.export-dynamics-solution.outputs.solution-file-path }}"
```