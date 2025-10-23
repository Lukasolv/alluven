package com.alluven.myapplication.network.dto

data class AuthResponse(
    val success: Boolean,
    val message: String? = null,
    val token: String? = null,
    val user: UserDTO? = null
)

data class UserDTO(
    val id: Int,
    val name: String,
    val email: String,
    val role: String
)

data class BasicResponse(
    val success: Boolean,
    val message: String? = null
)