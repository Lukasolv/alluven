package com.alluven.myapplication.network.dto

data class SearchResultDTO(
    val id: Int,
    val name: String,
    val phone: String?,
    val email: String?
)