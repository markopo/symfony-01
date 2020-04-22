Feature: Manage Blog Posts
  @createSchema
  Scenario: Create a blog post
    Given I am authenticated as "admin"
    When I add "Content-Type" header equal to "application/ld+json"
    And I add "Accept" header equal to "application/ld+json"
    And I send a "POST" request to "api/blog_posts" with body:
    """
      {
        "title": "test title 222 random text 20200416 kdslksd ",
        "published": "2020-04-15 08:00:00",
        "text": "text bla bla bla bla bla bla bla bla bla"
      }
    """
    Then the response status code should be 201
    And the response should be in JSON
