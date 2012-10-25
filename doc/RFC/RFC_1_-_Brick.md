# RFC 1 - Brick

This RFC is about how **users** create/manage **bricks**.

To comprehend better the keywords used in this document, [ read the dictionary.](https://github.com/inmarelibero/SymfonyBricks/blob/master/doc/dictionary.md)

## Purpose

A **user** must be able to sumbit to SymfonyBricks a arbitrary number of **bricks**.

## Structure of a brick

A **brick** has the following attributes:

- **title** (translated)
- **description**  (translated)
- **content**  (translated)
- **tags**: n:m relation with *tag* table
- **user**: the owner (linked to fos user)
- **created_at**
- **updated_at**
        
## Use case

When a user submits a **brick**, it happens the follow:

- **user** specifies the *title* (eg: *"How to integrate Botostrap in Symfony2"*), the *subtitle* and the *content* of the **brick**
- **user** specifies the *language* of the **brick**
- **user** (optionally) adds some **_tags_**
- **user** decides to publish the **brick** or to keep it as a draft

## _Content_ Syntax

To guarantee an high consistency among the syntax/format of the **bricks**, [Markdown format](http://daringfireball.net/projects/markdown/syntax) is chosen. This means that the *content* attribute of a **brick** is always interpreted following the rules of [Markdown format](http://daringfireball.net/projects/markdown/syntax).

The preferred tool to achieve this is [KnpMarkdownBundle](https://github.com/KnpLabs/KnpMarkdownBundle).

A set of syntax standards must be available for the **users**, to help him to create a **brick** following standard rules. This rules include the syntax to use when specifying a title, or a subtitle representing a step, or a block of code, etc...


## Translations

**Bricks** submitted in different languages are managed through [Translatable Doctrine Extension](https://github.com/l3pp4rd/DoctrineExtensions/blob/master/doc/translatable.md).

## Additional features

As the development goes further, a smart system to collaborate among users is higly desiderable. A generic user should be able to:

- rate a **brick**
- comment a line or a block of code/text
- comment the **brick**
- suggest a better implementation
- suggest repeated or partially repeated **bricks**

## Discussion
[RFC 1 - Brick](https://github.com/inmarelibero/SymfonyBricks/blob/master/doc/RFC/RFC_1_-_Brick.md) can be discussed in [issue #17](https://github.com/inmarelibero/SymfonyBricks/issues/17).