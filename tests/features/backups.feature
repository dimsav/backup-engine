Feature: Backups on local system
  In order to backup my projects
  As an administrator
  I need to save my projects in zipped archives locally

  Scenario: Backup project with two directories in paths
    Given I run the app using the project "test-1"
    Then  I should see a folder named "test-1" in the backups directory
    And   The backup folder of project "test-1" should contain 1 file of type "zip"
    And   The log file exists