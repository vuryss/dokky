# Dokky

A PHP Library to generate OpenAPI Documentation programmatically and generate schemas from DTOs.

For use inside frameworks or bundles that want to dynamically generate OpenAPI documentation.

## Background

I used a Symfony bundle nelmio/api-doc-bundle to convert DTOs to OpenAPI specs, however it's not meant for dynamic 
generation, but more for adding annotations directly on top of endpoints, which makes it awkward to use outside that
context.

On top of that I could not find a library with a simple OpenAPI Mappings that I could use as a base, so I also try to 
generate the OpenAPI mapping myself here.
